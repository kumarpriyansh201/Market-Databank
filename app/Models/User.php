<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'avatar', 'bio', 'is_active', 'google_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isContributor(): bool
    {
        return $this->role === 'contributor';
    }

    public function isUser(): bool
    {
        return $this->role === 'viewer';
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'admin' => 'Admin',
            'contributor' => 'Contributor',
            default => 'User',
        };
    }

    public function marketData()
    {
        return $this->hasMany(MarketData::class);
    }

    public function savedDatasets()
    {
        return $this->belongsToMany(MarketData::class, 'saved_datasets')
            ->withTimestamps();
    }

    public function datasetInteractions()
    {
        return $this->hasMany(DatasetInteraction::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    public function unreadNotifications()
    {
        return $this->userNotifications()->where('is_read', false);
    }
}
