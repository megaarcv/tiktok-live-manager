<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TiktokAccount;
use App\Models\LiveSession;
use App\Models\ScheduledLive;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $accountIds = TiktokAccount::where('user_id', $user->id)->pluck('id');

        $totalAccounts   = $accountIds->count();
        $activeSessions  = LiveSession::whereIn('tiktok_account_id', $accountIds)
            ->where('status', 'live')
            ->count();
        $totalSessions   = LiveSession::whereIn('tiktok_account_id', $accountIds)->count();
        $upcomingLives   = ScheduledLive::whereIn('tiktok_account_id', $accountIds)
            ->where('status', 'upcoming')
            ->where('scheduled_at', '>', now())
            ->count();

        $totalViewers  = LiveSession::whereIn('tiktok_account_id', $accountIds)->sum('total_viewers');
        $totalLikes    = LiveSession::whereIn('tiktok_account_id', $accountIds)->sum('total_likes');
        $totalDiamonds = LiveSession::whereIn('tiktok_account_id', $accountIds)->sum('diamonds_earned');

        $recentSessions = LiveSession::with('tiktokAccount')
            ->whereIn('tiktok_account_id', $accountIds)
            ->latest()
            ->take(5)
            ->get();

        $upcomingSchedules = ScheduledLive::with('tiktokAccount')
            ->whereIn('tiktok_account_id', $accountIds)
            ->where('status', 'upcoming')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        $accounts = TiktokAccount::where('user_id', $user->id)
            ->withCount('liveSessions')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalAccounts',
            'activeSessions',
            'totalSessions',
            'upcomingLives',
            'totalViewers',
            'totalLikes',
            'totalDiamonds',
            'recentSessions',
            'upcomingSchedules',
            'accounts'
        ));
    }
}
