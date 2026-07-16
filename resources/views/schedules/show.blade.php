@extends('layouts.app')
@section('title', $schedule->title.' — TikTok Live Manager')
@section('page-title', 'Detail Jadwal')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('schedules.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke jadwal
    </a>
    <div class="flex gap-2">
        <a href="{{ route('schedules.edit', $schedule) }}" class="btn-secondary text-xs py-1.5 px-3">Edit</a>
        <form method="POST" action="{{ route('schedules.destroy', $schedule) }}"
              onsubmit="return confirm('Hapus jadwal ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger text-xs py-1.5 px-3">Hapus</button>
        </form>
    </div>
</div>

<div class="max-w-2xl">
    <div class="card">
        {{-- Header --}}
        <div class="flex items-start gap-4 mb-6 pb-6 border-b border-gray-800">
            <div class="flex-shrink-0 w-14 text-center bg-gradient-to-br from-pink-500/20 to-cyan-400/20 border border-pink-500/20 rounded-2xl p-2">
                <p class="text-2xl font-bold text-white leading-none">{{ $schedule->scheduled_at->format('d') }}</p>
                <p class="text-xs text-gray-400 uppercase font-semibold">{{ $schedule->scheduled_at->format('M') }}</p>
                <p class="text-xs text-gray-500">{{ $schedule->scheduled_at->format('Y') }}</p>
            </div>
            <div class="flex-1 min-w-0">
                @php
                    $badgeColor = match($schedule->status) {
                        'completed' => 'green', 'cancelled' => 'gray',
                        'live' => 'red', default => 'blue',
                    };
                    $statusLabel = match($schedule->status) {
                        'completed' => 'Selesai', 'cancelled' => 'Dibatalkan',
                        'live' => 'Sedang Live', default => 'Upcoming',
                    };
                @endphp
                <div class="flex items-center gap-2 mb-1">
                    <span class="badge-{{ $badgeColor }}">{{ $statusLabel }}</span>
                </div>
                <h2 class="text-xl font-bold text-white mb-1">{{ $schedule->title }}</h2>
                <p class="text-sm text-gray-400">@{{ $schedule->tiktokAccount->username }}</p>
            </div>
        </div>

        {{-- Details --}}
        <dl class="space-y-4">
            <div class="flex gap-4">
                <dt class="text-sm text-gray-500 w-32 flex-shrink-0">Waktu Live</dt>
                <dd class="text-sm text-white">{{ $schedule->scheduled_at->format('l, d F Y') }} pukul {{ $schedule->scheduled_at->format('H:i') }} WIB</dd>
            </div>
            <div class="flex gap-4">
                <dt class="text-sm text-gray-500 w-32 flex-shrink-0">Durasi Est.</dt>
                <dd class="text-sm text-white">{{ $schedule->estimated_duration_minutes }} menit</dd>
            </div>
            @if($schedule->topic)
            <div class="flex gap-4">
                <dt class="text-sm text-gray-500 w-32 flex-shrink-0">Topik</dt>
                <dd class="text-sm text-white">{{ $schedule->topic }}</dd>
            </div>
            @endif
            @if($schedule->description)
            <div class="flex gap-4">
                <dt class="text-sm text-gray-500 w-32 flex-shrink-0">Deskripsi</dt>
                <dd class="text-sm text-gray-300">{{ $schedule->description }}</dd>
            </div>
            @endif
            @if($schedule->notes)
            <div class="flex gap-4">
                <dt class="text-sm text-gray-500 w-32 flex-shrink-0">Catatan</dt>
                <dd class="text-sm text-gray-300">{{ $schedule->notes }}</dd>
            </div>
            @endif
            <div class="flex gap-4">
                <dt class="text-sm text-gray-500 w-32 flex-shrink-0">Dibuat</dt>
                <dd class="text-sm text-gray-400">{{ $schedule->created_at->format('d M Y, H:i') }}</dd>
            </div>
        </dl>

        @if($schedule->status === 'upcoming' && $schedule->scheduled_at->isFuture())
            <div class="mt-6 pt-6 border-t border-gray-800 p-4 rounded-xl bg-blue-500/5 border-blue-500/20">
                <p class="text-sm text-blue-400 font-medium">
                    ⏳ {{ $schedule->scheduled_at->diffForHumans() }}
                </p>
            </div>
        @endif
    </div>
</div>

@endsection
