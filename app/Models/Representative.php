<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Representative extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'country',
        'country_code',
        'region',
        'phone',
        'address',
        'status',
    ];

    /**
     * Get the user that owns this representative profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all patients for this representative
     */
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    /**
     * Get all orders for this representative
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope for active representatives
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
