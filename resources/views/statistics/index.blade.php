@extends('layouts.app')
@section('title', 'Statistik — TikTok Live Manager')
@section('page-title', 'Statistik & Analitik')

@section('content')

{{-- Period Filter --}}
<div class="flex items-center gap-3 mb-6">
    <span class="text-sm text-gray-400">Periode:</span>
    <div class="flex gap-1">
        @foreach([7 => '7 Hari', 30 => '30 Hari', 90 => '90 Hari', 365 => '1 Tahun'] as $days => $label)
            <a href="{{ route('statistics.index', ['period' => $days]) }}"
               class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
                      {{ $period == $days ? 'bg-pink-500 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>

{{-- Overall Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs text-gray-400">Total Sesi</p>
            <span class="text-lg">🎬</span>
        </div>
        <p class="text-2xl font-bold text-white">{{ number_format($totalSessions) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $periodSessions }} dalam {{ $period }} hari</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs text-gray-400">Total Penonton</p>
            <span class="text-lg">👥</span>
        </div>
        <p class="text-2xl font-bold text-white">{{ number_format($totalViewers) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ number_format($periodViewers) }} dalam {{ $period }} hari</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs text-gray-400">Total Likes</p>
            <span class="text-lg">❤️</span>
        </div>
        <p class="text-2xl font-bold text-white">{{ number_format($totalLikes) }}</p>
        <p class="text-xs text-gray-500 mt-1">Seluruh sesi</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs text-gray-400">Total Diamonds</p>
            <span class="text-lg">💎</span>
        </div>
        <p class="text-2xl font-bold text-white">{{ number_format($totalDiamonds) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ number_format($periodDiamonds) }} dalam {{ $period }} hari</p>
    </div>
</div>

{{-- Averages --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="card">
        <h3 class="text-sm font-semibold text-gray-300 mb-4">Rata-rata per Sesi</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-400">Penonton</span>
                <span class="text-sm font-semibold text-white">{{ number_format($avgViewers, 0) }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-400">Durasi</span>
                <span class="text-sm font-semibold text-white">
                    @php
                        $avgMin = intdiv((int)$avgDuration, 60);
                        $avgSec = (int)$avgDuration % 60;
                    @endphp
                    {{ $avgMin }}m {{ $avgSec }}s
                </span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-400">Total Durasi</span>
                <span class="text-sm font-semibold text-white">
                    @php
                        $totalHours = intdiv($totalDuration, 3600);
                        $totalMins  = intdiv($totalDuration % 3600, 60);
                    @endphp
                    {{ $totalHours }}j {{ $totalMins }}m
                </span>
            </div>
        </div>
    </div>

    {{-- Daily Activity Chart (text-based) --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-gray-300 mb-4">Aktivitas 30 Hari Terakhir</h3>
        @if($dailyData->isEmpty())
            <p class="text-sm text-gray-500 text-center py-4">Tidak ada data dalam periode ini</p>
        @else
            @php $maxSessions = $dailyData->max('sessions') ?: 1; @endphp
            <div class="flex items-end gap-1 h-16">
                @foreach($dailyData->takeLast(30) as $day)
                    @php $height = max(4, (int)(($day->sessions / $maxSessions) * 100)); @endphp
                    <div class="flex-1 group relative">
                        <div class="bg-pink-500/60 hover:bg-pink-500 rounded-t transition-colors"
                             style="height: {{ $height }}%;"
                             title="{{ $day->date }}: {{ $day->sessions }} sesi"></div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-1">
                <span class="text-xs text-gray-600">{{ $dailyData->first()->date ?? '' }}</span>
                <span class="text-xs text-gray-600">{{ $dailyData->last()->date ?? '' }}</span>
            </div>
        @endif
    </div>
</div>

{{-- Top Sessions --}}
<div class="card mb-6">
    <h3 class="text-base font-semibold text-white mb-4">🏆 Top 5 Sesi Live Terbaik</h3>
    @if($topSessions->isEmpty())
        <p class="text-sm text-gray-500 text-center py-4">Belum ada data sesi</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-800">
                        <th class="text-left py-2 px-3 text-xs font-medium text-gray-400">#</th>
                        <th class="text-left py-2 px-3 text-xs font-medium text-gray-400">Sesi</th>
                        <th class="text-right py-2 px-3 text-xs font-medium text-gray-400">Penonton</th>
                        <th class="text-right py-2 px-3 text-xs font-medium text-gray-400 hidden sm:table-cell">Likes</th>
                        <th class="text-right py-2 px-3 text-xs font-medium text-gray-400 hidden md:table-cell">Diamonds</th>
                        <th class="text-right py-2 px-3 text-xs font-medium text-gray-400 hidden lg:table-cell">Durasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50">
                    @foreach($topSessions as $i => $session)
                        <tr class="hover:bg-gray-800/30 transition-colors">
                            <td class="py-2.5 px-3 text-gray-500 font-medium">
                                {{ $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : $i + 1)) }}
                            </td>
                            <td class="py-2.5 px-3">
                                <a href="{{ route('sessions.show', $session) }}" class="font-medium text-white hover:text-pink-300 transition-colors">
                                    {{ $session->title ?: 'Sesi #'.$session->id }}
                                </a>
                                <p class="text-xs text-gray-500">@{{ $session->tiktokAccount->username }}</p>
                            </td>
                            <td class="py-2.5 px-3 text-right font-semibold text-white">{{ number_format($session->total_viewers) }}</td>
                            <td class="py-2.5 px-3 text-right text-gray-300 hidden sm:table-cell">{{ number_format($session->total_likes) }}</td>
                            <td class="py-2.5 px-3 text-right text-yellow-400 hidden md:table-cell">💎 {{ number_format($session->diamonds_earned) }}</td>
                            <td class="py-2.5 px-3 text-right text-gray-300 hidden lg:table-cell">{{ $session->formatted_duration }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Per Account Stats --}}
<div class="card">
    <h3 class="text-base font-semibold text-white mb-4">Performa per Akun</h3>
    @if($accountStats->isEmpty())
        <p class="text-sm text-gray-500 text-center py-4">Belum ada akun terdaftar</p>
    @else
        <div class="space-y-3">
            @foreach($accountStats->sortByDesc('live_sessions_sum_total_viewers') as $account)
                @php
                    $maxViewers = $accountStats->max('live_sessions_sum_total_viewers') ?: 1;
                    $pct = $maxViewers > 0 ? (($account->live_sessions_sum_total_viewers ?? 0) / $maxViewers * 100) : 0;
                @endphp
                <div class="p-3 rounded-xl bg-gray-800/50">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-pink-500 to-cyan-400 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($account->username, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-white">@{{ $account->username }}</span>
                        </div>
                        <div class="text-right text-xs text-gray-400">
                            <span class="font-semibold text-white">{{ number_format($account->live_sessions_sum_total_viewers ?? 0) }}</span> penonton
                            · <span class="font-semibold text-yellow-400">{{ number_format($account->live_sessions_sum_diamonds_earned ?? 0) }}</span> 💎
                            · <span>{{ $account->live_sessions_count }} sesi</span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-1.5">
                        <div class="bg-gradient-to-r from-pink-500 to-cyan-400 h-1.5 rounded-full transition-all"
                             style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
