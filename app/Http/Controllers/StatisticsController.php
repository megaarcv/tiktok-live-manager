<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TiktokAccount;
use App\Models\LiveSession;

class StatisticsController extends Controller
{
    public function index(Request $request): View
    {
        $user       = Auth::user();
        $accountIds = TiktokAccount::where('user_id', $user->id)->pluck('id');

        $period = $request->get('period', '30');

        $dateFrom = now()->subDays((int) $period)->startOfDay();

        // Overall totals
        $totalSessions = LiveSession::whereIn('tiktok_account_id', $accountIds)->count();
        $totalViewers  = LiveSession::whereIn('tiktok_account_id', $accountIds)->sum('total_viewers');
        $totalLikes    = LiveSession::whereIn('tiktok_account_id', $accountIds)->sum('total_likes');
        $totalDiamonds = LiveSession::whereIn('tiktok_account_id', $accountIds)->sum('diamonds_earned');
        $totalDuration = LiveSession::whereIn('tiktok_account_id', $accountIds)->sum('duration_seconds');

        $avgViewers  = LiveSession::whereIn('tiktok_account_id', $accountIds)->avg('total_viewers') ?? 0;
        $avgDuration = LiveSession::whereIn('tiktok_account_id', $accountIds)->avg('duration_seconds') ?? 0;

        // Period stats
        $periodSessions = LiveSession::whereIn('tiktok_account_id', $accountIds)
            ->where('started_at', '>=', $dateFrom)
            ->count();

        $periodViewers  = LiveSession::whereIn('tiktok_account_id', $accountIds)
            ->where('started_at', '>=', $dateFrom)
            ->sum('total_viewers');

        $periodDiamonds = LiveSession::whereIn('tiktok_account_id', $accountIds)
            ->where('started_at', '>=', $dateFrom)
            ->sum('diamonds_earned');

        // Daily sessions for chart (last 30 days)
        $dailyData = LiveSession::whereIn('tiktok_account_id', $accountIds)
            ->where('started_at', '>=', now()->subDays(30)->startOfDay())
            ->select(
                DB::raw('DATE(started_at) as date'),
                DB::raw('COUNT(*) as sessions'),
                DB::raw('SUM(total_viewers) as viewers'),
                DB::raw('SUM(diamonds_earned) as diamonds')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top performing sessions
        $topSessions = LiveSession::with('tiktokAccount')
            ->whereIn('tiktok_account_id', $accountIds)
            ->orderBy('total_viewers', 'desc')
            ->take(5)
            ->get();

        // Per-account breakdown
        $accountStats = TiktokAccount::where('user_id', $user->id)
            ->withCount('liveSessions')
            ->withSum('liveSessions', 'total_viewers')
            ->withSum('liveSessions', 'diamonds_earned')
            ->get();

        $accounts = TiktokAccount::where('user_id', $user->id)->get();

        return view('statistics.index', compact(
            'totalSessions',
            'totalViewers',
            'totalLikes',
            'totalDiamonds',
            'totalDuration',
            'avgViewers',
            'avgDuration',
            'periodSessions',
            'periodViewers',
            'periodDiamonds',
            'dailyData',
            'topSessions',
            'accountStats',
            'accounts',
            'period'
        ));
    }
}
