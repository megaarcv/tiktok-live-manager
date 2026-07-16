<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TiktokAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'display_name',
        'avatar_url',
        'tiktok_uid',
        'followers_count',
        'following_count',
        'total_likes',
        'status',
        'notes',
    ];

    protected $casts = [
        'followers_count' => 'integer',
        'following_count' => 'integer',
        'total_likes' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function liveSessions(): HasMany
    {
        return $this->hasMany(LiveSession::class);
    }

    public function scheduledLives(): HasMany
    {
        return $this->hasMany(ScheduledLive::class);
    }

    public function activeLiveSession(): ?LiveSession
    {
        return $this->liveSessions()->where('status', 'live')->latest()->first();
    }

    public function getFormattedFollowersAttribute(): string
    {
        $count = $this->followers_count;
        if ($count >= 1_000_000) {
            return number_format($count / 1_000_000, 1) . 'M';
        }
        if ($count >= 1_000) {
            return number_format($count / 1_000, 1) . 'K';
        }
        return (string) $count;
    }

    public function getTotalLivesAttribute(): int
    {
        return $this->liveSessions()->count();
    }
}
