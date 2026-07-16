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
@endsection
