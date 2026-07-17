@extends('layouts.guest')
@section('title', 'Daftar — TikTok Live Manager')

@section('content')
<h2 class="text-xl font-bold text-white mb-1">Buat akun baru</h2>
<p class="text-sm text-gray-400 mb-6">Mulai kelola sesi live TikTok-mu</p>

@if ($errors->any())
    <div class="mb-4 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm">
        <ul class="space-y-1">
            @foreach ($errors->all() as $error)
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $error }}
                </li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf

    <div>
        <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">Nama Lengkap</label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name') }}"
            required
            autocomplete="name"
            placeholder="Nama kamu"
            class="auth-input @error('name') border-red-500/60 @enderror"
        >
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            required
            autocomplete="email"
            placeholder="nama@email.com"
            class="auth-input @error('email') border-red-500/60 @enderror"
        >
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            required
            autocomplete="new-password"
            placeholder="Minimal 8 karakter"
            class="auth-input @error('password') border-red-500/60 @enderror"
        >
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1.5">Konfirmasi Password</label>
        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            required
            autocomplete="new-password"
            placeholder="Ulangi password"
            class="auth-input"
        >
    </div>

    <button type="submit" class="btn-primary w-full">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        Buat Akun
    </button>
</form>

<p class="mt-6 text-center text-sm text-gray-400">
    Sudah punya akun?
    <a href="{{ route('login') }}" class="text-pink-400 hover:text-pink-300 font-medium transition-colors">
        Masuk di sini
    </a>
</p>

{{-- Divider --}}
<div class="relative my-6">
    <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-gray-700"></div>
    </div>
    <div class="relative flex justify-center text-xs">
        <span class="bg-gray-900 px-3 text-gray-500">atau</span>
    </div>
</div>

{{-- Tombol Google --}}
<a href="{{ route('auth.google') }}"
   class="flex items-center justify-center gap-3 w-full px-4 py-2.5 rounded-xl
          bg-white hover:bg-gray-100 text-gray-800 font-medium text-sm
          border border-gray-200 transition-all duration-150 shadow-sm">
    <svg class="w-5 h-5" viewBox="0 0 24 24">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
    </svg>
    Daftar dengan Google
</a>
@endsection
