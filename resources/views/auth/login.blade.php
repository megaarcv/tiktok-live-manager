@extends('layouts.guest')
@section('title', 'Login — TikTok Live Manager')

@section('content')
<h2 class="text-xl font-bold text-white mb-1">Selamat datang kembali</h2>
<p class="text-sm text-gray-400 mb-6">Masuk ke akun Anda untuk melanjutkan</p>

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

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

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
            class="auth-input @error('email') border-red-500/60 focus:ring-red-500/30 @enderror"
        >
    </div>

    <div>
        <div class="flex items-center justify-between mb-1.5">
            <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
        </div>
        <input
            type="password"
            id="password"
            name="password"
            required
            autocomplete="current-password"
            placeholder="••••••••"
            class="auth-input @error('password') border-red-500/60 focus:ring-red-500/30 @enderror"
        >
    </div>

    <div class="flex items-center justify-between">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-600 bg-gray-800 text-pink-500 focus:ring-pink-500/30">
            <span class="text-sm text-gray-400">Ingat saya</span>
        </label>
    </div>

    <button type="submit" class="btn-primary w-full">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
        </svg>
        Masuk
    </button>
</form>

<p class="mt-6 text-center text-sm text-gray-400">
    Belum punya akun?
    <a href="{{ route('register') }}" class="text-pink-400 hover:text-pink-300 font-medium transition-colors">
        Daftar sekarang
    </a>
</p>
@endsection
