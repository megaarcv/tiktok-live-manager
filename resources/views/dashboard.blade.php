@extends('layouts.app')
@section('title', 'Dashboard — TikTok Live Manager')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Akun</span>
            <div class="p-2 rounded-lg bg-pink-500/10">
                <svg class="w-4 h-4 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $totalAccounts }}</p>
        <p class="text-xs text-gray-500 mt-1">Akun TikTok terdaftar</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Sedang Live</span>
            <div class="p-2 rounded-lg bg-red-500/10">
                <span class="relative flex h-4 w-4">
                    <span class="{{ $activeSessions > 0 ? 'animate-ping' : '' }} absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 items-center justify-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    </span>
                </span>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $activeSessions }}</p>
        <p class="text-xs text-gray-500 mt-1">Sesi aktif sekarang</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Sesi</span>
            <div class="p-2 rounded-lg bg-cyan-500/10">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.258a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $totalSessions }}</p>
        <p class="text-xs text-gray-500 mt-1">Sesi live tercatat</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Jadwal</span>
            <div class="p-2 rounded-lg bg-purple-500/10">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $upcomingLives }}</p>
        <p class="text-xs text-gray-500 mt-1">Live akan datang</p>
    </div>
</div>

{{-- Performance Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-gradient-to-br from-pink-500/10 to-pink-600/5 border border-pink-500/20 rounded-2xl p-4">
        <p class="text-xs font-medium text-pink-400 mb-1">Total Penonton</p>
        <p class="text-xl font-bold text-white">{{ number_format($totalViewers) }}</p>
    </div>
    <div class="bg-gradient-to-br from-red-500/10 to-red-600/5 border border-red-500/20 rounded-2xl p-4">
        <p class="text-xs font-medium text-red-400 mb-1">Total Likes</p>
        <p class="text-xl font-bold text-white">{{ number_format($totalLikes) }}</p>
    </div>
    <div class="bg-gradient-to-br from-yellow-500/10 to-yellow-600/5 border border-yellow-500/20 rounded-2xl p-4">
        <p class="text-xs font-medium text-yellow-400 mb-1">Total Diamonds</p>
        <p class="text-xl font-bold text-white">💎 {{ number_format($totalDiamonds) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Recent Sessions --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-white">Sesi Live Terbaru</h2>
            <a href="{{ route('sessions.index') }}" class="text-xs text-pink-400 hover:text-pink-300 transition-colors">
                Lihat semua →
            </a>
        </div>

        @if($recentSessions->isEmpty())
            <div class="text-center py-8">
                <svg class="w-10 h-10 text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.258a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-gray-500">Belum ada sesi live</p>
                <a href="{{ route('sessions.create') }}" class="text-xs text-pink-400 hover:text-pink-300 mt-1 inline-block">
                    + Tambah sesi
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($recentSessions as $session)
                    <a href="{{ route('sessions.show', $session) }}" class="flex items-center gap-3 p-3 rounded-xl bg-gray-800/50 hover:bg-gray-800 transition-colors group">
                        <div class="flex-shrink-0">
                            @if($session->status === 'live')
                                <span class="relative flex h-8 w-8 items-center justify-center">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-40"></span>
                                    <span class="relative inline-flex h-8 w-8 rounded-full bg-red-500/20 border border-red-500/40 items-center justify-center">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                    </span>
                                </span>
                            @else
                                <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728M9 10l4.553-2.069A1 1 0 0115 8.87v6.258a1 1 0 01-1.447.894L9 14"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate group-hover:text-pink-300 transition-colors">
                                {{ $session->title ?: '@'.$session->tiktokAccount->username }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $session->tiktokAccount->username }} · {{ $session->started_at?->diffForHumans() ?? 'Baru' }}
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs font-medium {{ $session->status === 'live' ? 'text-red-400' : 'text-gray-400' }}">
                                {{ $session->status === 'live' ? 'LIVE' : 'Selesai' }}
                            </p>
                            <p class="text-xs text-gray-500">{{ number_format($session->total_viewers) }} penonton</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Upcoming Schedules --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-white">Jadwal Mendatang</h2>
            <a href="{{ route('schedules.index') }}" class="text-xs text-pink-400 hover:text-pink-300 transition-colors">
                Lihat semua →
            </a>
        </div>

        @if($upcomingSchedules->isEmpty())
            <div class="text-center py-8">
                <svg class="w-10 h-10 text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-gray-500">Tidak ada jadwal mendatang</p>
                <a href="{{ route('schedules.create') }}" class="text-xs text-pink-400 hover:text-pink-300 mt-1 inline-block">
                    + Buat jadwal
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($upcomingSchedules as $schedule)
                    <a href="{{ route('schedules.show', $schedule) }}" class="flex items-center gap-3 p-3 rounded-xl bg-gray-800/50 hover:bg-gray-800 transition-colors group">
                        <div class="flex-shrink-0 w-10 text-center">
                            <p class="text-lg font-bold text-white leading-none">{{ $schedule->scheduled_at->format('d') }}</p>
                            <p class="text-xs text-gray-400 uppercase">{{ $schedule->scheduled_at->format('M') }}</p>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate group-hover:text-pink-300 transition-colors">
                                {{ $schedule->title }}
                            </p>
                            <p class="text-xs text-gray-500">
                                @{{ $schedule->tiktokAccount->username }} · {{ $schedule->scheduled_at->format('H:i') }}
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge-blue">Upcoming</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Quick Actions --}}
<div class="mt-6">
    <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Aksi Cepat</h2>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a href="{{ route('accounts.create') }}" class="quick-action-btn">
            <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Tambah Akun</span>
        </a>
        <a href="{{ route('sessions.create') }}" class="quick-action-btn">
            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.258a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <span>Catat Sesi</span>
        </a>
        <a href="{{ route('schedules.create') }}" class="quick-action-btn">
            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Buat Jadwal</span>
        </a>
        <a href="{{ route('statistics.index') }}" class="quick-action-btn">
            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span>Statistik</span>
        </a>
    </div>
</div>

@endsection
