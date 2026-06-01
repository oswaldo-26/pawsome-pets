<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'species',
        'breed',
        'age_group',
        'age_months',
        'gender',
        'size',
        'description',
        'photo',
        'is_vaccinated',
        'is_neutered',
        'is_housetrained',
        'good_with_kids',
        'good_with_pets',
        'status',
    ];

    protected $casts = [
        'is_vaccinated' => 'boolean',
        'is_neutered' => 'boolean',
        'is_housetrained' => 'boolean',
        'good_with_kids' => 'boolean',
        'good_with_pets' => 'boolean',
    ];

    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    // ── SCOPES ──

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeBySpecies($query, $species)
    {
        return $species ? $query->where('species', $species) : $query;
    }

    public function scopeByAgeGroup($query, $ageGroup)
    {
        return $ageGroup ? $query->where('age_group', $ageGroup) : $query;
    }

    public function scopeByGender($query, $gender)
    {
        return $gender ? $query->where('gender', $gender) : $query;
    }

    public function getPhotoUrlAttribute()
    {
        if (!$this->photo)
            return null;

        // If it's an external URL return as is
        if (str_starts_with($this->photo, 'http')) {
            return $this->photo;
        }

        return asset('storage/' . $this->photo);
    }

    public function getSpeciesEmojiAttribute()
    {
        return match ($this->species) {
            'dog' => '🐶',
            'cat' => '🐱',
            'small_pet' => '🐹',
            default => '🐾',
        };
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }
}