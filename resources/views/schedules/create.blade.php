@extends('layouts.app')
@section('title', 'Buat Jadwal Live')
@section('page-title', 'Buat Jadwal Live')

@section('content')

<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('schedules.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke jadwal
        </a>
    </div>

    <div class="card">
        <h2 class="text-base font-semibold text-white mb-6">Detail Jadwal Live</h2>

        <form method="POST" action="{{ route('schedules.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
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

                <div class="sm:col-span-2">
                    <label for="title" class="form-label">Judul Live <span class="text-red-400">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                           class="form-input @error('title') border-red-500/60 @enderror"
                           placeholder="Judul atau tema live" required>
                    @error('title')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="scheduled_at" class="form-label">Tanggal & Waktu <span class="text-red-400">*</span></label>
                    <input type="datetime-local" id="scheduled_at" name="scheduled_at"
                           value="{{ old('scheduled_at') }}"
                           class="form-input @error('scheduled_at') border-red-500/60 @enderror" required>
                    @error('scheduled_at')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="estimated_duration_minutes" class="form-label">Durasi Estimasi (menit)</label>
                    <input type="number" id="estimated_duration_minutes" name="estimated_duration_minutes"
                           value="{{ old('estimated_duration_minutes', 60) }}"
                           class="form-input @error('estimated_duration_minutes') border-red-500/60 @enderror"
                           min="1" max="720" placeholder="60">
                    @error('estimated_duration_minutes')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="topic" class="form-label">Topik</label>
                    <input type="text" id="topic" name="topic" value="{{ old('topic') }}"
                           class="form-input @error('topic') border-red-500/60 @enderror"
                           placeholder="Topik atau kategori live (opsional)">
                    @error('topic')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea id="description" name="description" rows="3"
                              class="form-input @error('description') border-red-500/60 @enderror"
                              placeholder="Deskripsi singkat tentang live ini...">{{ old('description') }}</textarea>
                    @error('description')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="notes" class="form-label">Catatan Internal</label>
                    <textarea id="notes" name="notes" rows="2"
                              class="form-input @error('notes') border-red-500/60 @enderror"
                              placeholder="Catatan persiapan, dll...">{{ old('notes') }}</textarea>
                    @error('notes')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Jadwal
                </button>
                <a href="{{ route('schedules.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
