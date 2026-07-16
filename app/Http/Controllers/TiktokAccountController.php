<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TiktokAccount;

class TiktokAccountController extends Controller
{
    public function index(): View
    {
        $accounts = TiktokAccount::where('user_id', Auth::id())
            ->withCount('liveSessions')
            ->latest()
            ->paginate(10);

        return view('accounts.index', compact('accounts'));
    }

    public function create(): View
    {
        return view('accounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username'        => ['required', 'string', 'max:100'],
            'display_name'    => ['nullable', 'string', 'max:150'],
            'tiktok_uid'      => ['nullable', 'string', 'max:100'],
            'followers_count' => ['nullable', 'integer', 'min:0'],
            'following_count' => ['nullable', 'integer', 'min:0'],
            'total_likes'     => ['nullable', 'integer', 'min:0'],
            'status'          => ['required', 'in:active,inactive,suspended'],
            'notes'           => ['nullable', 'string', 'max:1000'],
        ]);

        $data['user_id'] = Auth::id();

        TiktokAccount::create($data);

        return redirect()->route('accounts.index')
            ->with('success', 'Akun TikTok berhasil ditambahkan!');
    }

    public function show(TiktokAccount $account): View
    {
        $this->authorizeAccount($account);

        $account->loadCount('liveSessions');
        $recentSessions = $account->liveSessions()
            ->latest()
            ->take(10)
            ->get();

        $totalViewers  = $account->liveSessions()->sum('total_viewers');
        $totalLikes    = $account->liveSessions()->sum('total_likes');
        $totalDiamonds = $account->liveSessions()->sum('diamonds_earned');
        $avgViewers    = $account->liveSessions()->avg('total_viewers') ?? 0;

        return view('accounts.show', compact(
            'account',
            'recentSessions',
            'totalViewers',
            'totalLikes',
            'totalDiamonds',
            'avgViewers'
        ));
    }

    public function edit(TiktokAccount $account): View
    {
        $this->authorizeAccount($account);

        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, TiktokAccount $account): RedirectResponse
    {
        $this->authorizeAccount($account);

        $data = $request->validate([
            'username'        => ['required', 'string', 'max:100'],
            'display_name'    => ['nullable', 'string', 'max:150'],
            'tiktok_uid'      => ['nullable', 'string', 'max:100'],
            'followers_count' => ['nullable', 'integer', 'min:0'],
            'following_count' => ['nullable', 'integer', 'min:0'],
            'total_likes'     => ['nullable', 'integer', 'min:0'],
            'status'          => ['required', 'in:active,inactive,suspended'],
            'notes'           => ['nullable', 'string', 'max:1000'],
        ]);

        $account->update($data);

        return redirect()->route('accounts.show', $account)
            ->with('success', 'Akun TikTok berhasil diperbarui!');
    }

    public function destroy(TiktokAccount $account): RedirectResponse
    {
        $this->authorizeAccount($account);

        $account->delete();

        return redirect()->route('accounts.index')
            ->with('success', 'Akun TikTok berhasil dihapus!');
    }

    private function authorizeAccount(TiktokAccount $account): void
    {
        if ($account->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
}
