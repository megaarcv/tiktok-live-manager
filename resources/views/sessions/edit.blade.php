@extends('layouts.app')
@section('title', 'Edit Sesi Live')
@section('page-title', 'Edit Sesi Live')

@section('content')

<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('sessions.show', $session) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke detail sesi
        </a>
    </div>

    <div class="card">
        <h2 class="text-base font-semibold text-white mb-6">Edit Informasi Sesi Live</h2>

        <form method="POST" action="{{ route('sessions.update', $session) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="tiktok_account_id" class="form-label">Akun TikTok <span class="text-red-400">*</span></label>
                    <select id="tiktok_account_id" name="tiktok_account_id"
                            class="form-input @error('tiktok_account_id') border-red-500/60 @enderror" required>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}"
                                {{ old('tiktok_account_id', $session->tiktok_account_id) == $account->id ? 'selected' : '' }}>
                                @{{ $account->username }}{{ $account->display_name ? ' — '.$account->display_name : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('tiktok_account_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="status" class="form-label">Status <span class="text-red-400">*</span></label>
                    <select id="status" name="status" class="form-input @error('status') border-red-500/60 @enderror">
                        <option value="ended" {{ old('status', $session->status) === 'ended' ? 'selected' : '' }}>Selesai</option>
                        <option value="live"  {{ old('status', $session->status) === 'live'  ? 'selected' : '' }}>Sedang Live</option>
                    </select>
                    @error('status')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="title" class="form-label">Judul Live</label>
                <input type="text" id="title" name="title" value="{{ old('title', $session->title) }}"
                       class="form-input @error('title') border-red-500/60 @enderror"
                       placeholder="Judul atau tema live">
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="started_at" class="form-label">Waktu Mulai</label>
                    <input type="datetime-local" id="started_at" name="started_at"
                           value="{{ old('started_at', $session->started_at?->format('Y-m-d\TH:i')) }}"
                           class="form-input @error('started_at') border-red-500/60 @enderror">
                    @error('started_at')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="ended_at" class="form-label">Waktu Selesai</label>
                    <input type="datetime-local" id="ended_at" name="ended_at"
                           value="{{ old('ended_at', $session->ended_at?->format('Y-m-d\TH:i')) }}"
                           class="form-input @error('ended_at') border-red-500/60 @enderror">
                    @error('ended_at')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="border-t border-gray-800 pt-5">
                <h3 class="text-sm font-semibold text-gray-300 mb-4">Statistik Sesi</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach([
                        ['peak_viewers',   'Peak Penonton'],
                        ['total_viewers',  'Total Penonton'],
                        ['total_likes',    'Total Likes'],
                        ['total_comments', 'Total Komentar'],
                        ['total_shares',   'Total Share'],
                        ['diamonds_earned','💎 Diamonds'],
                    ] as [$field, $label])
                    <div>
                        <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                        <input type="number" id="{{ $field }}" name="{{ $field }}"
                               value="{{ old($field, $session->$field) }}" min="0"
                               class="form-input @error($field) border-red-500/60 @enderror">
                        @error($field)<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    @endforeach
                </div>
            </div>

            <div>
                <label for="notes" class="form-label">Catatan</label>
                <textarea id="notes" name="notes" rows="3"
                          class="form-input @error('notes') border-red-500/60 @enderror">{{ old('notes', $session->notes) }}</textarea>
                @error('notes')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('sessions.show', $session) }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
