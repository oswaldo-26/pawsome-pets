<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdoptionRequest extends Model
{
    use HasFactory;

    // Lahat ng columns na idinagdag sa fillable array gaya ng nasa guideline niyo
    protected $fillable = [
        'user_id',
        'pet_id',
        'status',
        'notes'
    ];

    // belongsTo User relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // belongsTo Pet relationship
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    // hasMany Notification relationship (kumokonekta sa custom table via adoption_request_id)
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'adoption_request_id');
    }
}