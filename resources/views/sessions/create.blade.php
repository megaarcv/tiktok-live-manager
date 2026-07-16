@extends('layouts.app')
@section('title', 'Catat Sesi Live')
@section('page-title', 'Catat Sesi Live')

@section('content')

<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('sessions.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke daftar sesi
        </a>
    </div>

    <div class="card">
        <h2 class="text-base font-semibold text-white mb-6">Informasi Sesi Live</h2>

        <form method="POST" action="{{ route('sessions.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="tiktok_account_id" class="form-label">Akun TikTok <span class="text-red-400">*</span></label>
                    <select id="tiktok_account_id" name="tiktok_account_id"
                            class="form-input @error('tiktok_account_id') border-red-500/60 @enderror" required>
                        <option value="">Pilih akun...</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('tiktok_account_id') == $account->id ? 'selected' : '' }}>
                                @{{ $account->username }}{{ $account->display_name ? ' — '.$account->display_name : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('tiktok_account_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="status" class="form-label">Status <span class="text-red-400">*</span></label>
                    <select id="status" name="status"
                            class="form-input @error('status') border-red-500/60 @enderror">
                        <option value="ended" {{ old('status', 'ended') === 'ended' ? 'selected' : '' }}>Selesai</option>
                        <option value="live"  {{ old('status') === 'live'  ? 'selected' : '' }}>Sedang Live</option>
                    </select>
                    @error('status')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="title" class="form-label">Judul Live</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}"
                       class="form-input @error('title') border-red-500/60 @enderror"
                       placeholder="Judul atau tema live (opsional)">
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="started_at" class="form-label">Waktu Mulai</label>
                    <input type="datetime-local" id="started_at" name="started_at"
                           value="{{ old('started_at') }}"
                           class="form-input @error('started_at') border-red-500/60 @enderror">
                    @error('started_at')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="ended_at" class="form-label">Waktu Selesai</label>
                    <input type="datetime-local" id="ended_at" name="ended_at"
                           value="{{ old('ended_at') }}"
                           class="form-input @error('ended_at') border-red-500/60 @enderror">
                    @error('ended_at')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="border-t border-gray-800 pt-5">
                <h3 class="text-sm font-semibold text-gray-300 mb-4">Statistik Sesi</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="peak_viewers" class="form-label">Peak Penonton</label>
                        <input type="number" id="peak_viewers" name="peak_viewers"
                               value="{{ old('peak_viewers', 0) }}" min="0"
                               class="form-input @error('peak_viewers') border-red-500/60 @enderror">
                        @error('peak_viewers')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="total_viewers" class="form-label">Total Penonton</label>
                        <input type="number" id="total_viewers" name="total_viewers"
                               value="{{ old('total_viewers', 0) }}" min="0"
                               class="form-input @error('total_viewers') border-red-500/60 @enderror">
                        @error('total_viewers')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="total_likes" class="form-label">Total Likes</label>
                        <input type="number" id="total_likes" name="total_likes"
                               value="{{ old('total_likes', 0) }}" min="0"
                               class="form-input @error('total_likes') border-red-500/60 @enderror">
                        @error('total_likes')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="total_comments" class="form-label">Total Komentar</label>
                        <input type="number" id="total_comments" name="total_comments"
                               value="{{ old('total_comments', 0) }}" min="0"
                               class="form-input @error('total_comments') border-red-500/60 @enderror">
                        @error('total_comments')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="total_shares" class="form-label">Total Share</label>
                        <input type="number" id="total_shares" name="total_shares"
                               value="{{ old('total_shares', 0) }}" min="0"
                               class="form-input @error('total_shares') border-red-500/60 @enderror">
                        @error('total_shares')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="diamonds_earned" class="form-label">💎 Diamonds</label>
                        <input type="number" id="diamonds_earned" name="diamonds_earned"
                               value="{{ old('diamonds_earned', 0) }}" min="0"
                               class="form-input @error('diamonds_earned') border-red-500/60 @enderror">
                        @error('diamonds_earned')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div>
                <label for="notes" class="form-label">Catatan</label>
                <textarea id="notes" name="notes" rows="3"
                          class="form-input @error('notes') border-red-500/60 @enderror"
                          placeholder="Catatan tambahan tentang sesi ini...">{{ old('notes') }}</textarea>
                @error('notes')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Sesi
                </button>
                <a href="{{ route('sessions.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
