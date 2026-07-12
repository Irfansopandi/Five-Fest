<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorWithdrawal extends Model
{
    use HasFactory;

    protected $table = 'vendor_withdrawals';

    protected $fillable = [
        'vendor_id',
        'event_id',
        'amount',
        'bank_name',
        'account_number',
        'account_holder',
        'status', // pending, approved, rejected
        'notes'
    ];

    /**
     * Relasi ke Vendor (User)
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
