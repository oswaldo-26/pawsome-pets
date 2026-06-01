<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdoptionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pet_id',
        'status',
        'occupation',
        'home_type',
        'has_yard',
        'has_other_pets',
        'has_children',
        'reason',
        'experience',
        'admin_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'has_yard' => 'boolean',
        'has_other_pets' => 'boolean',
        'has_children' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'adoption_request_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => '⏳ Pending',
            'approved' => '✅ Approved',
            'rejected' => '❌ Rejected',
            'completed' => '🏡 Completed',
            default => $this->status,
        };
    }
}