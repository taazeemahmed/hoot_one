<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is representative
     */
    public function isRepresentative(): bool
    {
        return $this->role === 'representative';
    }

    /**
     * Check if user is marketing member
     */
    public function isMarketingMember(): bool
    {
        return $this->role === 'marketing_member';
    }

    /**
     * Get the representative profile for the user
     */
    public function representative()
    {
        return $this->hasOne(Representative::class);
    }

    /**
     * Get the marketing targets for the user
     */
    public function marketingTargets()
    {
        return $this->hasMany(MarketingTarget::class);
    }
}
