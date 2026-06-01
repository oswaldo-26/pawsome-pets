<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    /**
     * IMPORTANT: Sabi sa guideline, dahil 'notifs' ang pangalan ng table 
     * sa database at hindi 'notifications', kailangan itong i-set nang explicit.
     */
    protected $table = 'notifs';

    /**
     * Lahat ng columns na kailangan para sa notification fields.
     */
    protected $fillable = [
        'user_id',
        'adoption_request_id',
        'title',
        'message',
        'type',
        'is_read'
    ];

    /**
     * belongsTo User relationship
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * belongsTo AdoptionRequest relationship
     */
    public function adoptionRequest(): BelongsTo
    {
        return $this->belongsTo(AdoptionRequest::class);
    }
}