<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'live_session_id',
        'commenter_username',
        'commenter_display_name',
        'content',
        'is_pinned',
        'is_highlighted',
        'is_filtered',
        'commented_at',
    ];

    protected $casts = [
        'commented_at'   => 'datetime',
        'is_pinned'      => 'boolean',
        'is_highlighted' => 'boolean',
        'is_filtered'    => 'boolean',
    ];

    public function liveSession(): BelongsTo
    {
        return $this->belongsTo(LiveSession::class);
    }
}
