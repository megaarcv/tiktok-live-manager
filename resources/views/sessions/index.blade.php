@extends('layouts.app')
@section('title', 'Sesi Live — TikTok Live Manager')
@section('page-title', 'Sesi Live')

@section('content')

{{-- Filter Bar --}}
<div class="flex flex-col sm:flex-row gap-3 mb-6">
    <form method="GET" action="{{ route('sessions.index') }}" class="flex flex-1 gap-3 flex-wrap">
        <select name="status" class="form-input w-auto"
                onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="live"  {{ request('status') === 'live'  ? 'selected' : '' }}>Sedang Live</option>
            <option value="ended" {{ request('status') === 'ended' ? 'selected' : '' }}>Selesai</option>
        </select>

        <select name="account_id" class="form-input w-auto"
                onchange="this.form.submit()">
            <option value="">Semua Akun</option>
            @foreach($accounts as $account)
                <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                    @{{ $account->username }}
                </option>
            @endforeach
        </select>

        @if(request('status') || request('account_id'))
            <a href="{{ route('sessions.index') }}" class="btn-secondary text-xs">Reset</a>
        @endif
    </form>

    <a href="{{ route('sessions.create') }}" class="btn-primary flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Catat Sesi
    </a>
</div>

@if($sessions->isEmpty())
    <div class="card text-center py-16">
        <svg class="w-12 h-12 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.258a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
        <h3 class="text-lg font-semibold text-white mb-2">Belum ada sesi live</h3>
        <p class="text-sm text-gray-400 mb-6">Mulai catat sesi live TikTok kamu</p>
        <a href="{{ route('sessions.create') }}" class="btn-primary inline-flex mx-auto">+ Catat Sesi Live</a>
    </div>
@else
    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-800 bg-gray-800/40">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Akun / Judul</th>
                        <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden sm:table-cell">Penonton</th>
                        <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">Likes</th>
                        <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden lg:table-cell">Diamonds</th>
                        <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">Durasi</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50">
                    @foreach($sessions as $session)
                        <tr class="hover:bg-gray-800/30 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    @if($session->status === 'live')
                                        <span class="relative flex-shrink-0">
                                            <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-red-400 opacity-60"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full h-3 w-3 bg-gray-600 flex-shrink-0"></span>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-medium text-white truncate max-w-[180px]">
                                            {{ $session->title ?: 'Sesi #'.$session->id }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            @{{ $session->tiktokAccount->username }} · {{ $session->started_at?->format('d M Y') ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-right text-gray-300 hidden sm:table-cell">
                                {{ number_format($session->total_viewers) }}
                            </td>
                            <td class="py-3 px-4 text-right text-gray-300 hidden md:table-cell">
                                {{ number_format($session->total_likes) }}
                            </td>
                            <td class="py-3 px-4 text-right text-yellow-400 hidden lg:table-cell">
                                💎 {{ number_format($session->diamonds_earned) }}
                            </td>
                            <td class="py-3 px-4 text-right text-gray-300 hidden md:table-cell">
                                {{ $session->formatted_duration }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge-{{ $session->status === 'live' ? 'red' : 'gray' }}">
                                    {{ $session->status === 'live' ? 'LIVE' : 'Selesai' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('sessions.show', $session) }}"
                                       class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors"
                                       title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('sessions.edit', $session) }}"
                                       class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors"
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('sessions.destroy', $session) }}"
                                          onsubmit="return confirm('Hapus sesi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 text-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors"
                                                title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $sessions->withQueryString()->links() }}
    </div>
@endif

@endsection
