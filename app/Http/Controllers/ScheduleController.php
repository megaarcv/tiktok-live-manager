<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\ScheduledLive;
use App\Models\TiktokAccount;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $user       = Auth::user();
        $accountIds = TiktokAccount::where('user_id', $user->id)->pluck('id');

        $query = ScheduledLive::with('tiktokAccount')
            ->whereIn('tiktok_account_id', $accountIds);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $upcoming  = (clone $query)->where('status', 'upcoming')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at')
            ->get();

        $past = (clone $query)->where(function ($q) {
            $q->where('status', 'completed')
              ->orWhere('status', 'cancelled')
              ->orWhere(function ($q2) {
                  $q2->where('status', 'upcoming')
                     ->where('scheduled_at', '<=', now());
              });
        })->orderBy('scheduled_at', 'desc')->paginate(10);

        $accounts = TiktokAccount::where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        return view('schedules.index', compact('upcoming', 'past', 'accounts'));
    }

    public function create(): View
    {
        $accounts = TiktokAccount::where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();

        return view('schedules.create', compact('accounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tiktok_account_id'          => ['required', 'exists:tiktok_accounts,id'],
            'title'                      => ['required', 'string', 'max:255'],
            'description'                => ['nullable', 'string', 'max:1000'],
            'scheduled_at'               => ['required', 'date', 'after:now'],
            'estimated_duration_minutes' => ['nullable', 'integer', 'min:1', 'max:720'],
            'topic'                      => ['nullable', 'string', 'max:150'],
            'notes'                      => ['nullable', 'string', 'max:1000'],
        ]);

        $this->authorizeAccountOwnership($data['tiktok_account_id']);

        $data['status'] = 'upcoming';

        ScheduledLive::create($data);

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal live berhasil ditambahkan!');
    }

    public function show(ScheduledLive $schedule): View
    {
        $this->authorizeSchedule($schedule);
        $schedule->load('tiktokAccount');

        return view('schedules.show', compact('schedule'));
    }

    public function edit(ScheduledLive $schedule): View
    {
        $this->authorizeSchedule($schedule);

        $accounts = TiktokAccount::where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();

        return view('schedules.edit', compact('schedule', 'accounts'));
    }

    public function update(Request $request, ScheduledLive $schedule): RedirectResponse
    {
        $this->authorizeSchedule($schedule);

        $data = $request->validate([
            'tiktok_account_id'          => ['required', 'exists:tiktok_accounts,id'],
            'title'                      => ['required', 'string', 'max:255'],
            'description'                => ['nullable', 'string', 'max:1000'],
            'scheduled_at'               => ['required', 'date'],
            'estimated_duration_minutes' => ['nullable', 'integer', 'min:1', 'max:720'],
            'status'                     => ['required', 'in:upcoming,live,completed,cancelled'],
            'topic'                      => ['nullable', 'string', 'max:150'],
            'notes'                      => ['nullable', 'string', 'max:1000'],
        ]);

        $schedule->update($data);

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal live berhasil diperbarui!');
    }

    public function destroy(ScheduledLive $schedule): RedirectResponse
    {
        $this->authorizeSchedule($schedule);

        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal live berhasil dihapus!');
    }

    private function authorizeSchedule(ScheduledLive $schedule): void
    {
        $accountIds = TiktokAccount::where('user_id', Auth::id())->pluck('id');
        if (!$accountIds->contains($schedule->tiktok_account_id)) {
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
