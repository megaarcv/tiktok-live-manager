<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'live_session_id',
        'viewers',
        'likes',
        'comments',
        'shares',
        'diamonds',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'viewers'     => 'integer',
        'likes'       => 'integer',
        'comments'    => 'integer',
        'shares'      => 'integer',
        'diamonds'    => 'integer',
    ];

    public function liveSession(): BelongsTo
    {
        return $this->belongsTo(LiveSession::class);
    }
}
