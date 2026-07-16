@extends('layouts.app')
@section('title', 'Jadwal Live — TikTok Live Manager')
@section('page-title', 'Jadwal Live')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-400">{{ $upcoming->count() }} jadwal mendatang</p>
    <a href="{{ route('schedules.create') }}" class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Buat Jadwal
    </a>
</div>

{{-- Upcoming --}}
<div class="mb-8">
    <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Akan Datang</h2>

    @if($upcoming->isEmpty())
        <div class="card text-center py-10">
            <svg class="w-10 h-10 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm text-gray-500">Tidak ada jadwal mendatang</p>
            <a href="{{ route('schedules.create') }}" class="text-xs text-pink-400 hover:text-pink-300 mt-2 inline-block">
                + Buat jadwal live
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($upcoming as $schedule)
                <div class="card hover:border-gray-700 transition-colors">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="flex-shrink-0 w-12 text-center bg-gray-800 rounded-xl p-2">
                            <p class="text-xl font-bold text-white leading-none">{{ $schedule->scheduled_at->format('d') }}</p>
                            <p class="text-xs text-gray-400 uppercase font-medium">{{ $schedule->scheduled_at->format('M') }}</p>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-white text-sm leading-tight truncate">{{ $schedule->title }}</h3>
                            <p class="text-xs text-gray-400 mt-0.5">@{{ $schedule->tiktokAccount->username }}</p>
                        </div>
                        <span class="badge-blue flex-shrink-0">Upcoming</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mb-4 text-xs">
                        <div class="bg-gray-800/60 rounded-lg p-2">
                            <p class="text-gray-500">Waktu</p>
                            <p class="text-white font-medium">{{ $schedule->scheduled_at->format('H:i') }} WIB</p>
                        </div>
                        <div class="bg-gray-800/60 rounded-lg p-2">
                            <p class="text-gray-500">Durasi est.</p>
                            <p class="text-white font-medium">{{ $schedule->estimated_duration_minutes }} menit</p>
                        </div>
                    </div>

                    @if($schedule->topic)
                        <div class="mb-4">
                            <span class="text-xs text-gray-500">Topik: </span>
                            <span class="text-xs text-gray-300">{{ $schedule->topic }}</span>
                        </div>
                    @endif

                    <div class="text-xs text-gray-500 mb-4">
                        {{ $schedule->scheduled_at->diffForHumans() }}
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('schedules.show', $schedule) }}" class="btn-secondary flex-1 justify-center text-xs">
                            Detail
                        </a>
                        <a href="{{ route('schedules.edit', $schedule) }}" class="btn-secondary px-2.5 text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('schedules.destroy', $schedule) }}"
                              onsubmit="return confirm('Hapus jadwal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger px-2.5 text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- History --}}
<div>
    <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Riwayat Jadwal</h2>

    @if($past->isEmpty())
        <div class="card text-center py-8">
            <p class="text-sm text-gray-500">Belum ada riwayat jadwal</p>
        </div>
    @else
        <div class="card overflow-hidden p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 bg-gray-800/40">
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Judul</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden sm:table-cell">Akun</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">Waktu</th>
                            <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50">
                        @foreach($past as $schedule)
                            <tr class="hover:bg-gray-800/30 transition-colors">
                                <td class="py-3 px-4">
                                    <p class="font-medium text-white">{{ $schedule->title }}</p>
                                    @if($schedule->topic)
                                        <p class="text-xs text-gray-500">{{ $schedule->topic }}</p>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-gray-400 hidden sm:table-cell">
                                    @{{ $schedule->tiktokAccount->username }}
                                </td>
                                <td class="py-3 px-4 text-gray-400 hidden md:table-cell">
                                    {{ $schedule->scheduled_at->format('d M Y, H:i') }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @php
                                        $badgeColor = match($schedule->status) {
                                            'completed' => 'green',
                                            'cancelled' => 'gray',
                                            'live'      => 'red',
                                            default     => 'blue',
                                        };
                                        $statusLabel = match($schedule->status) {
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Batal',
                                            'live'      => 'Live',
                                            default     => 'Pending',
                                        };
                                    @endphp
                                    <span class="badge-{{ $badgeColor }}">{{ $statusLabel }}</span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('schedules.show', $schedule) }}"
                                           class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('schedules.edit', $schedule) }}"
                                           class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $past->links() }}
        </div>
    @endif
</div>

@endsection
