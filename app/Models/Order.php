<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'medicine_id',
        'representative_id',
        'packs_ordered',
        'treatment_start_date',
        'expected_renewal_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'treatment_start_date' => 'date',
        'expected_renewal_date' => 'date',
    ];

    protected $appends = ['days_until_renewal', 'renewal_status'];

    /**
     * Get the patient for this order
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the medicine for this order
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    /**
     * Get the representative for this order
     */
    public function representative()
    {
        return $this->belongsTo(Representative::class);
    }

    /**
     * Calculate expected renewal date based on packs ordered
     */
    public static function calculateRenewalDate($startDate, $packsOrdered, $packDurationDays = 30)
    {
        return Carbon::parse($startDate)->addDays($packsOrdered * $packDurationDays);
    }

    /**
     * Scope for upcoming renewals within given days
     */
    public function scopeUpcomingRenewals($query, $days = 30)
    {
        return $query->where('status', 'active')
                     ->whereBetween('expected_renewal_date', [
                         Carbon::today(),
                         Carbon::today()->addDays($days)
                     ]);
    }

    /**
     * Scope for overdue renewals
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'active')
                     ->where('expected_renewal_date', '<', Carbon::today());
    }

    /**
     * Get days until renewal
     */
    public function getDaysUntilRenewalAttribute()
    {
        return Carbon::today()->diffInDays($this->expected_renewal_date, false);
    }

    /**
     * Get renewal status (urgent, upcoming, normal)
     */
    public function getRenewalStatusAttribute()
    {
        $days = $this->days_until_renewal;
        
        if ($days < 0) return 'overdue';
        if ($days <= 7) return 'urgent';
        if ($days <= 14) return 'upcoming';
        return 'normal';
    }

    /**
     * Scope for active orders
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
