<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPeserta(): bool
    {
        return $this->role === 'peserta';
    }

    public function testAssignments(): HasMany
    {
        return $this->hasMany(TestAssignment::class);
    }

    public function testSessions(): HasMany
    {
        return $this->hasMany(TestSession::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function materialProgress(): HasMany
    {
        return $this->hasMany(UserMaterialProgress::class);
    }

    public function practiceAttempts(): HasMany
    {
        return $this->hasMany(PracticeAttempt::class);
    }
}
