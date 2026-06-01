<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'species',
    'breed',
    'age_group',
    'gender',
    'size',
    'description',
    'photo',
    'is_vaccinated',
    'is_neutered',
    'good_with_kids',
    'good_with_pets',
    'status'
])]
class Pet extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_vaccinated' => 'boolean',
            'is_neutered' => 'boolean',
            'good_with_kids' => 'boolean',
            'good_with_pets' => 'boolean',
        ];
    }

    /**
     * Get the adoption requests for the pet.
     */
    public function adoptionRequests(): HasMany
    {
        return $this->hasMany(AdoptionRequest::class);
    }
}