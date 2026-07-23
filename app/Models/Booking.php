<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'ticket_category_id',
        'booking_code',
        'seat_number',
        'start_seat_number',
        'snap_token',
        'identity_number',
        'birth_date',
        'gender',
        'phone',
        'quantity',
        'total_price',
        'payment_method',
        'payment_status',
        'booking_status',
        'tax_remitted',
        'tax_receipt',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'tax_remitted' => 'boolean',
    ];

    // Relationship ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship ke Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // FIX: Tambahkan Relationship ke TicketCategory
    public function ticket_category()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function merchandises()
    {
        return $this->belongsToMany(Merchandise::class, 'booking_merchandise')
                    ->withPivot(
                        'quantity', 
                        'price',
                        'size',
                        'is_collected', 
                        'collected_at'
                    )
                    ->withTimestamps();
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Generate unique booking code
    public static function generateBookingCode()
    {
        do {
            // Format lebih rapi ala FiveFest: FF-RANDOM8
            $code = 'FF-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }

    // Check if booking is confirmed
    public function isConfirmed()
    {
        return $this->payment_status === 'paid' && $this->booking_status === 'confirmed';
    }

    
}