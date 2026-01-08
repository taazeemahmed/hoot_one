<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'pack_duration_days',
        'price',
        'status',
    ];

    /**
     * Get all orders for this medicine
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope for active medicines
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
