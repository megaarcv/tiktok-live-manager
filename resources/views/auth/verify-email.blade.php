@extends('layouts.guest')
@section('title', 'Verifikasi Email — TikTok Live Manager')

@section('content')
<div class="text-center">
    <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-500/20 to-cyan-400/20 border border-pink-500/20 mx-auto mb-5">
        <svg class="w-8 h-8 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
    </div>

    <h2 class="text-xl font-bold text-white mb-2">Verifikasi Email Kamu</h2>
    <p class="text-sm text-gray-400 mb-6">
        Kami sudah mengirim link verifikasi ke
        <span class="text-white font-medium">{{ Auth::user()->email }}</span>.
        Cek inbox atau folder spam-mu.
    </p>

    @if(session('resent'))
        <div class="mb-5 flex items-center gap-2 px-4 py-3 rounded-xl bg-green-500/10 border border-green-500/30 text-green-400 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Email verifikasi berhasil dikirim ulang!
        </div>
    @endif

    <div class="space-y-3">
        {{-- Kirim ulang --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary w-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-secondary w-full">
                Logout
            </button>
        </form>
    </div>

    <p class="mt-6 text-xs text-gray-500">
        Pastikan email yang kamu daftarkan benar. Jika tidak menerima email dalam beberapa menit, cek folder spam.
    </p>
</div>
@endsection
