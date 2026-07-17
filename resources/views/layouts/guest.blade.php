<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TikTok Live Manager')</title>
    @php
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        $cssFile = $manifest['resources/css/app.css']['file'] ?? 'assets/app.css';
        $jsFile  = $manifest['resources/js/app.js']['file']  ?? 'assets/app.js';
    @endphp
    <link rel="stylesheet" href="/build/{{ $cssFile }}">
    <script src="/build/{{ $jsFile }}" defer></script>
</head>
<body class="min-h-full bg-gray-950 font-sans">
    <div class="min-h-screen flex items-start justify-center p-4 py-10">
        <div class="w-full max-w-md">
            {{-- Logo --}}
            <div class="flex flex-col items-center mb-8">
                <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-cyan-400 shadow-xl shadow-pink-500/25 mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">TikTok Live Manager</h1>
                <p class="text-sm text-gray-400 mt-1">Kelola semua sesi live TikTok-mu</p>
            </div>

            {{-- Card --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8 shadow-2xl">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
