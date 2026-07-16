<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledLive extends Model
{
    use HasFactory;

    protected $fillable = [
        'tiktok_account_id',
        'title',
        'description',
        'scheduled_at',
        'estimated_duration_minutes',
        'status',
        'topic',
        'notes',
    ];

    protected $casts = [
        'scheduled_at'               => 'datetime',
        'estimated_duration_minutes' => 'integer',
    ];

    public function tiktokAccount(): BelongsTo
    {
        return $this->belongsTo(TiktokAccount::class);
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->status === 'upcoming' && $this->scheduled_at->isFuture();
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'upcoming'  => 'blue',
            'live'      => 'red',
            'completed' => 'green',
            'cancelled' => 'gray',
            default     => 'gray',
        };
    }
}
