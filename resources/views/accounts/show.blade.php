@extends('layouts.app')
@section('title', '@'.$account->username.' — TikTok Live Manager')
@section('page-title', '@'.$account->username)

@section('content')

<div class="mb-6">
    <a href="{{ route('accounts.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke daftar akun
    </a>
</div>

{{-- Profile Card --}}
<div class="card mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-500 to-cyan-400 flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
            {{ strtoupper(substr($account->username, 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <h2 class="text-xl font-bold text-white">@{{ $account->username }}</h2>
                <span class="badge-{{ $account->status === 'active' ? 'green' : ($account->status === 'inactive' ? 'gray' : 'red') }}">
                    {{ ucfirst($account->status) }}
                </span>
            </div>
            @if($account->display_name)
                <p class="text-gray-400 text-sm">{{ $account->display_name }}</p>
            @endif
            @if($account->tiktok_uid)
                <p class="text-xs text-gray-500 mt-0.5">UID: {{ $account->tiktok_uid }}</p>
            @endif
        </div>
        <div class="flex gap-2 flex-shrink-0">
            <a href="{{ route('accounts.edit', $account) }}" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <form method="POST" action="{{ route('accounts.destroy', $account) }}"
                  onsubmit="return confirm('Hapus akun @{{ $account->username }}? Semua data sesi live akan ikut terhapus.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    {{-- Metrics --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-800">
        <div class="text-center">
            <p class="text-xl font-bold text-white">{{ $account->formatted_followers }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Followers</p>
        </div>
        <div class="text-center">
            <p class="text-xl font-bold text-white">{{ number_format($account->following_count) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Following</p>
        </div>
        <div class="text-center">
            <p class="text-xl font-bold text-white">{{ number_format($account->live_sessions_count) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Total Live</p>
        </div>
        <div class="text-center">
            <p class="text-xl font-bold text-white">{{ number_format($account->total_likes / 1000, 0) }}K</p>
            <p class="text-xs text-gray-400 mt-0.5">Total Likes</p>
        </div>
    </div>

    @if($account->notes)
        <div class="mt-4 pt-4 border-t border-gray-800">
            <p class="text-xs text-gray-500 mb-1">Catatan</p>
            <p class="text-sm text-gray-300">{{ $account->notes }}</p>
        </div>
    @endif
</div>

{{-- Performance Summary --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <p class="text-xs text-gray-400 mb-1">Total Penonton</p>
        <p class="text-lg font-bold text-white">{{ number_format($totalViewers) }}</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-400 mb-1">Rata-rata Penonton</p>
        <p class="text-lg font-bold text-white">{{ number_format($avgViewers, 0) }}</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-400 mb-1">Total Likes</p>
        <p class="text-lg font-bold text-white">{{ number_format($totalLikes) }}</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-400 mb-1">Total Diamonds</p>
        <p class="text-lg font-bold text-white">💎 {{ number_format($totalDiamonds) }}</p>
    </div>
</div>

{{-- Recent Sessions --}}
<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-white">Riwayat Sesi Live</h3>
        <a href="{{ route('sessions.create') }}" class="btn-primary text-xs py-1.5 px-3">
            + Catat Sesi
        </a>
    </div>

    @if($recentSessions->isEmpty())
        <div class="text-center py-8">
            <p class="text-sm text-gray-500">Belum ada sesi live untuk akun ini</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-800">
                        <th class="text-left py-3 px-2 text-xs font-medium text-gray-400">Judul / Waktu</th>
                        <th class="text-right py-3 px-2 text-xs font-medium text-gray-400">Penonton</th>
                        <th class="text-right py-3 px-2 text-xs font-medium text-gray-400">Likes</th>
                        <th class="text-right py-3 px-2 text-xs font-medium text-gray-400">Diamonds</th>
                        <th class="text-right py-3 px-2 text-xs font-medium text-gray-400">Durasi</th>
                        <th class="text-right py-3 px-2 text-xs font-medium text-gray-400">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50">
                    @foreach($recentSessions as $session)
                        <tr class="hover:bg-gray-800/30 transition-colors">
                            <td class="py-3 px-2">
                                <a href="{{ route('sessions.show', $session) }}" class="font-medium text-white hover:text-pink-300 transition-colors">
                                    {{ $session->title ?: 'Sesi #'.$session->id }}
                                </a>
                                <p class="text-xs text-gray-500">
                                    {{ $session->started_at?->format('d M Y, H:i') ?? '-' }}
                                </p>
                            </td>
                            <td class="py-3 px-2 text-right text-gray-300">{{ number_format($session->total_viewers) }}</td>
                            <td class="py-3 px-2 text-right text-gray-300">{{ number_format($session->total_likes) }}</td>
                            <td class="py-3 px-2 text-right text-yellow-400">💎 {{ number_format($session->diamonds_earned) }}</td>
                            <td class="py-3 px-2 text-right text-gray-300">{{ $session->formatted_duration }}</td>
                            <td class="py-3 px-2 text-right">
                                <span class="badge-{{ $session->status === 'live' ? 'red' : 'gray' }}">
                                    {{ $session->status === 'live' ? 'LIVE' : 'Selesai' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
