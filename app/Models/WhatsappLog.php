<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappLog extends Model
{
    protected $fillable = [
        'patient_id',
        'order_id',
        'phone_number',
        'message_body',
        'status',
        'response',
        'sent_at'
    ];

    protected $casts = [
        'response' => 'array',
        'sent_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
