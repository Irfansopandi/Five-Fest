<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'tenant_id',
        'booth_photo',
        'business_name',
        'status',
        'payment_status',
        'snap_token',
        'midtrans_order_id',
        'refund_reason',
        'refund_status',
        'refund_requested_at',
        'refund_approved_at',
        'refund_completed_at',
        'refund_reject_reason',
    ];

    protected $casts = [
        'refund_requested_at' => 'datetime',
        'refund_approved_at'  => 'datetime',
        'refund_completed_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }
}