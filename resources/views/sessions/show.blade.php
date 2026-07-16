@extends('layouts.app')
@section('title', ($session->title ?: 'Sesi #'.$session->id).' — TikTok Live Manager')
@section('page-title', $session->title ?: 'Detail Sesi Live')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('sessions.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke daftar sesi
    </a>
    <div class="flex gap-2">
        <a href="{{ route('sessions.edit', $session) }}" class="btn-secondary text-xs py-1.5 px-3">Edit</a>
        <form method="POST" action="{{ route('sessions.destroy', $session) }}"
              onsubmit="return confirm('Hapus sesi ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger text-xs py-1.5 px-3">Hapus</button>
        </form>
    </div>
</div>

{{-- Session Header --}}
<div class="card mb-6">
    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-2 flex-wrap">
                @if($session->status === 'live')
                    <span class="relative flex items-center gap-1.5 text-sm font-semibold text-red-400">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                        </span>
                        SEDANG LIVE
                    </span>
                @else
                    <span class="badge-gray">Selesai</span>
                @endif
                <span class="text-gray-500 text-sm">
                    @{{ $session->tiktokAccount->username }}
                </span>
            </div>
            <h2 class="text-xl font-bold text-white mb-1">
                {{ $session->title ?: 'Sesi #'.$session->id }}
            </h2>
            <div class="flex items-center gap-4 text-xs text-gray-400 flex-wrap">
                @if($session->started_at)
                    <span>Mulai: {{ $session->started_at->format('d M Y, H:i') }}</span>
                @endif
                @if($session->ended_at)
                    <span>Selesai: {{ $session->ended_at->format('d M Y, H:i') }}</span>
                @endif
                @if($session->duration_seconds > 0)
                    <span>Durasi: {{ $session->formatted_duration }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    @foreach([
        ['label' => 'Peak Penonton', 'value' => number_format($session->peak_viewers),    'color' => 'cyan',   'icon' => '👀'],
        ['label' => 'Total Penonton','value' => number_format($session->total_viewers),   'color' => 'blue',   'icon' => '👥'],
        ['label' => 'Total Likes',   'value' => number_format($session->total_likes),     'color' => 'pink',   'icon' => '❤️'],
        ['label' => 'Komentar',      'value' => number_format($session->total_comments),  'color' => 'purple', 'icon' => '💬'],
        ['label' => 'Share',         'value' => number_format($session->total_shares),    'color' => 'green',  'icon' => '🔗'],
        ['label' => 'Diamonds',      'value' => number_format($session->diamonds_earned), 'color' => 'yellow', 'icon' => '💎'],
    ] as $stat)
    <div class="stat-card text-center">
        <p class="text-2xl mb-1">{{ $stat['icon'] }}</p>
        <p class="text-lg font-bold text-white">{{ $stat['value'] }}</p>
        <p class="text-xs text-gray-400">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Notes --}}
@if($session->notes)
    <div class="card mb-6">
        <h3 class="text-sm font-semibold text-gray-300 mb-2">Catatan</h3>
        <p class="text-sm text-gray-400">{{ $session->notes }}</p>
    </div>
@endif

{{-- Comments --}}
@if($session->comments->isNotEmpty())
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-white">
                Komentar
                <span class="ml-1.5 text-xs font-normal text-gray-400">({{ $session->comments->count() }})</span>
            </h3>
        </div>
        <div class="space-y-2 max-h-96 overflow-y-auto">
            @foreach($session->comments->take(50) as $comment)
                <div class="flex gap-3 p-2 rounded-lg {{ $comment->is_highlighted ? 'bg-yellow-500/10 border border-yellow-500/20' : 'hover:bg-gray-800/40' }} transition-colors">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-pink-500 to-cyan-400 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($comment->commenter_username, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-white">@{{ $comment->commenter_username }}</span>
                            <span class="text-xs text-gray-500">{{ $comment->commented_at->format('H:i') }}</span>
                            @if($comment->is_pinned)
                                <span class="text-xs text-yellow-400">📌</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-300 break-words">{{ $comment->content }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@endsection
