<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'representative_id',
        'name',
        'email',
        'phone',
        'country',
        'address',
        'notes',
    ];

    /**
     * Get the representative for this patient
     */
    public function representative()
    {
        return $this->belongsTo(Representative::class);
    }

    /**
     * Get all orders for this patient
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the latest order for this patient
     */
    public function latestOrder()
    {
        return $this->hasOne(Order::class)->latestOfMany();
    }
}
