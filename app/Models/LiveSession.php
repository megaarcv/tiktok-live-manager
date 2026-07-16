<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiveSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'tiktok_account_id',
        'title',
        'status',
        'started_at',
        'ended_at',
        'duration_seconds',
        'peak_viewers',
        'total_viewers',
        'total_likes',
        'total_comments',
        'total_shares',
        'diamonds_earned',
        'notes',
    ];

    protected $casts = [
        'started_at'       => 'datetime',
        'ended_at'         => 'datetime',
        'peak_viewers'     => 'integer',
        'total_viewers'    => 'integer',
        'total_likes'      => 'integer',
        'total_comments'   => 'integer',
        'total_shares'     => 'integer',
        'diamonds_earned'  => 'integer',
        'duration_seconds' => 'integer',
    ];

    public function tiktokAccount(): BelongsTo
    {
        return $this->belongsTo(TiktokAccount::class);
    }

    public function liveStats(): HasMany
    {
        return $this->hasMany(LiveStat::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getFormattedDurationAttribute(): string
    {
        $seconds = $this->duration_seconds;
        $hours   = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $secs    = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%dj %02dm %02ds', $hours, $minutes, $secs);
        }
        return sprintf('%dm %02ds', $minutes, $secs);
    }

    public function getIsLiveAttribute(): bool
    {
        return $this->status === 'live';
    }

    public function getFormattedDiamondsAttribute(): string
    {
        $d = $this->diamonds_earned;
        if ($d >= 1_000_000) return number_format($d / 1_000_000, 1) . 'M';
        if ($d >= 1_000) return number_format($d / 1_000, 1) . 'K';
        return (string) $d;
    }
}
