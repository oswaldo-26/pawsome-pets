<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'occupation',
        'home_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAdopter()
    {
        return $this->role === 'adopter';
    }

    public function unreadNotificationsCount()
    {
        return $this->notifications()->where('is_read', false)->count();
    }
}