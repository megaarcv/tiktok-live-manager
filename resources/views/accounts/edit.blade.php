@extends('layouts.app')
@section('title', 'Edit Akun — @'.$account->username)
@section('page-title', 'Edit Akun TikTok')

@section('content')

<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('accounts.show', $account) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke detail akun
        </a>
    </div>

    <div class="card">
        <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-800">
            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-pink-500 to-cyan-400 flex items-center justify-center text-white font-bold text-lg">
                {{ strtoupper(substr($account->username, 0, 1)) }}
            </div>
            <div>
                <p class="font-semibold text-white">@{{ $account->username }}</p>
                <p class="text-xs text-gray-400">Edit informasi akun</p>
            </div>
        </div>

        <form method="POST" action="{{ route('accounts.update', $account) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="username" class="form-label">Username TikTok <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">@</span>
                        <input type="text" id="username" name="username" value="{{ old('username', $account->username) }}"
                               class="form-input pl-7 @error('username') border-red-500/60 @enderror"
                               placeholder="namaakun" required>
                    </div>
                    @error('username')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="display_name" class="form-label">Nama Tampilan</label>
                    <input type="text" id="display_name" name="display_name" value="{{ old('display_name', $account->display_name) }}"
                           class="form-input @error('display_name') border-red-500/60 @enderror"
                           placeholder="Nama lengkap / panggilan">
                    @error('display_name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="tiktok_uid" class="form-label">TikTok UID</label>
                <input type="text" id="tiktok_uid" name="tiktok_uid" value="{{ old('tiktok_uid', $account->tiktok_uid) }}"
                       class="form-input @error('tiktok_uid') border-red-500/60 @enderror"
                       placeholder="ID unik TikTok (opsional)">
                @error('tiktok_uid')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label for="followers_count" class="form-label">Followers</label>
                    <input type="number" id="followers_count" name="followers_count"
                           value="{{ old('followers_count', $account->followers_count) }}"
                           class="form-input @error('followers_count') border-red-500/60 @enderror" min="0">
                    @error('followers_count')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="following_count" class="form-label">Following</label>
                    <input type="number" id="following_count" name="following_count"
                           value="{{ old('following_count', $account->following_count) }}"
                           class="form-input @error('following_count') border-red-500/60 @enderror" min="0">
                    @error('following_count')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="total_likes" class="form-label">Total Likes</label>
                    <input type="number" id="total_likes" name="total_likes"
                           value="{{ old('total_likes', $account->total_likes) }}"
                           class="form-input @error('total_likes') border-red-500/60 @enderror" min="0">
                    @error('total_likes')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="status" class="form-label">Status Akun <span class="text-red-400">*</span></label>
                <select id="status" name="status" class="form-input @error('status') border-red-500/60 @enderror">
                    <option value="active"    {{ old('status', $account->status) === 'active'    ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive"  {{ old('status', $account->status) === 'inactive'  ? 'selected' : '' }}>Tidak Aktif</option>
                    <option value="suspended" {{ old('status', $account->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
                @error('status')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="notes" class="form-label">Catatan</label>
                <textarea id="notes" name="notes" rows="3"
                          class="form-input @error('notes') border-red-500/60 @enderror"
                          placeholder="Catatan tambahan...">{{ old('notes', $account->notes) }}</textarea>
                @error('notes')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('accounts.show', $account) }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
