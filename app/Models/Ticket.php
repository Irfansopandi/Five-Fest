<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'ticket_code',
        'seat_number',
        'status',
        'scanned_at',
        'scanned_by',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function scannedBy()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }

    public static function generateTicketCode()
    {
        do {
            $code = 'TC-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
        } while (self::where('ticket_code', $code)->exists());

        return $code;
    }
}
