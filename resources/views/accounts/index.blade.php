@extends('layouts.app')
@section('title', 'Akun TikTok — TikTok Live Manager')
@section('page-title', 'Akun TikTok')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-400">{{ $accounts->total() }} akun terdaftar</p>
    <a href="{{ route('accounts.create') }}" class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Tambah Akun
    </a>
</div>

@if($accounts->isEmpty())
    <div class="card text-center py-16">
        <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-800 mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-white mb-2">Belum ada akun TikTok</h3>
        <p class="text-sm text-gray-400 mb-6">Tambahkan akun TikTok pertama kamu untuk mulai mengelola live</p>
        <a href="{{ route('accounts.create') }}" class="btn-primary inline-flex mx-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tambah Akun Pertama
        </a>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($accounts as $account)
            <div class="card hover:border-gray-700 transition-colors group">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-pink-500 to-cyan-400 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                            {{ strtoupper(substr($account->username, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-white truncate">@{{ $account->username }}</p>
                            @if($account->display_name)
                                <p class="text-xs text-gray-400 truncate">{{ $account->display_name }}</p>
                            @endif
                        </div>
                    </div>
                    <span class="badge-{{ $account->status === 'active' ? 'green' : ($account->status === 'inactive' ? 'gray' : 'red') }} flex-shrink-0">
                        {{ ucfirst($account->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-2 mb-4">
                    <div class="text-center p-2 rounded-lg bg-gray-800/60">
                        <p class="text-sm font-bold text-white">{{ $account->formatted_followers }}</p>
                        <p class="text-xs text-gray-500">Followers</p>
                    </div>
                    <div class="text-center p-2 rounded-lg bg-gray-800/60">
                        <p class="text-sm font-bold text-white">{{ $account->live_sessions_count }}</p>
                        <p class="text-xs text-gray-500">Sesi Live</p>
                    </div>
                    <div class="text-center p-2 rounded-lg bg-gray-800/60">
                        <p class="text-sm font-bold text-white">{{ number_format($account->total_likes / 1000, 0) }}K</p>
                        <p class="text-xs text-gray-500">Likes</p>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('accounts.show', $account) }}" class="btn-secondary flex-1 text-center justify-center">
                        Detail
                    </a>
                    <a href="{{ route('accounts.edit', $account) }}" class="btn-secondary px-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('accounts.destroy', $account) }}" onsubmit="return confirm('Hapus akun @{{ $account->username }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger px-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $accounts->links() }}
    </div>
@endif

@endsection
