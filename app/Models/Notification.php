<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifs';

    protected $fillable = [
        'user_id',
        'adoption_request_id',
        'title',
        'message',
        'type',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function adoptionRequest()
    {
        return $this->belongsTo(AdoptionRequest::class, 'adoption_request_id');
    }


    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function getTypeEmojiAttribute()
    {
        return match ($this->type) {
            'approved' => '✅',
            'rejected' => '❌',
            'pending' => '⏳',
            'completed' => '🏡',
            default => '🔔',
        };
    }
}