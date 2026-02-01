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
        'lead_status',
        'lead_quality',
        'source',
        'assigned_by',
        'assigned_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
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

    /**
     * Get user who assigned this lead
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Get activities for this lead
     */
    public function activities()
    {
        return $this->hasMany(LeadActivity::class)->latest();
    }

    /**
     * Get the latest activity for this lead
     */
    public function latestActivity()
    {
        return $this->hasOne(LeadActivity::class)->latestOfMany();
    }
}
