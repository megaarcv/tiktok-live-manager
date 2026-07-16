<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\LiveSession;
use App\Models\TiktokAccount;

class LiveSessionController extends Controller
{
    public function index(Request $request): View
    {
        $user       = Auth::user();
        $accountIds = TiktokAccount::where('user_id', $user->id)->pluck('id');

        $query = LiveSession::with('tiktokAccount')
            ->whereIn('tiktok_account_id', $accountIds);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('account_id')) {
            $query->where('tiktok_account_id', $request->account_id);
        }

        $sessions = $query->latest()->paginate(15);

        $accounts = TiktokAccount::where('user_id', $user->id)->get();

        return view('sessions.index', compact('sessions', 'accounts'));
    }

    public function create(): View
    {
        $accounts = TiktokAccount::where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();

        return view('sessions.create', compact('accounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tiktok_account_id' => ['required', 'exists:tiktok_accounts,id'],
            'title'             => ['nullable', 'string', 'max:255'],
            'status'            => ['required', 'in:live,ended'],
            'started_at'        => ['nullable', 'date'],
            'ended_at'          => ['nullable', 'date', 'after_or_equal:started_at'],
            'peak_viewers'      => ['nullable', 'integer', 'min:0'],
            'total_viewers'     => ['nullable', 'integer', 'min:0'],
            'total_likes'       => ['nullable', 'integer', 'min:0'],
            'total_comments'    => ['nullable', 'integer', 'min:0'],
            'total_shares'      => ['nullable', 'integer', 'min:0'],
            'diamonds_earned'   => ['nullable', 'integer', 'min:0'],
            'notes'             => ['nullable', 'string', 'max:1000'],
        ]);

        $this->authorizeAccountOwnership($data['tiktok_account_id']);

        // Calculate duration if both dates are set
        if (!empty($data['started_at']) && !empty($data['ended_at'])) {
            $data['duration_seconds'] = strtotime($data['ended_at']) - strtotime($data['started_at']);
        }

        LiveSession::create($data);

        return redirect()->route('sessions.index')
            ->with('success', 'Sesi live berhasil ditambahkan!');
    }

    public function show(LiveSession $session): View
    {
        $this->authorizeSession($session);

        $session->load('tiktokAccount', 'liveStats', 'comments');

        $chartData = $session->liveStats()
            ->orderBy('recorded_at')
            ->get(['recorded_at', 'viewers', 'likes', 'diamonds']);

        return view('sessions.show', compact('session', 'chartData'));
    }

    public function edit(LiveSession $session): View
    {
        $this->authorizeSession($session);

        $accounts = TiktokAccount::where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();

        return view('sessions.edit', compact('session', 'accounts'));
    }

    public function update(Request $request, LiveSession $session): RedirectResponse
    {
        $this->authorizeSession($session);

        $data = $request->validate([
            'tiktok_account_id' => ['required', 'exists:tiktok_accounts,id'],
            'title'             => ['nullable', 'string', 'max:255'],
            'status'            => ['required', 'in:live,ended'],
            'started_at'        => ['nullable', 'date'],
            'ended_at'          => ['nullable', 'date', 'after_or_equal:started_at'],
            'peak_viewers'      => ['nullable', 'integer', 'min:0'],
            'total_viewers'     => ['nullable', 'integer', 'min:0'],
            'total_likes'       => ['nullable', 'integer', 'min:0'],
            'total_comments'    => ['nullable', 'integer', 'min:0'],
            'total_shares'      => ['nullable', 'integer', 'min:0'],
            'diamonds_earned'   => ['nullable', 'integer', 'min:0'],
            'notes'             => ['nullable', 'string', 'max:1000'],
        ]);

        if (!empty($data['started_at']) && !empty($data['ended_at'])) {
            $data['duration_seconds'] = strtotime($data['ended_at']) - strtotime($data['started_at']);
        }

        $session->update($data);

        return redirect()->route('sessions.show', $session)
            ->with('success', 'Sesi live berhasil diperbarui!');
    }

    public function destroy(LiveSession $session): RedirectResponse
    {
        $this->authorizeSession($session);

        $session->delete();

        return redirect()->route('sessions.index')
            ->with('success', 'Sesi live berhasil dihapus!');
    }

    private function authorizeSession(LiveSession $session): void
    {
        $accountIds = TiktokAccount::where('user_id', Auth::id())->pluck('id');
        if (!$accountIds->contains($session->tiktok_account_id)) {
            abort(403, 'Unauthorized');
        }
    }

    private function authorizeAccountOwnership(int $accountId): void
    {
        $account = TiktokAccount::findOrFail($accountId);
        if ($account->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
}
