<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

protected $fillable = [
    'user_id',
    'title', 
    'artist', 
    'description', 
    'terms', 
    'terms_image',
    'venue', 
    'venue_map', 
    'venue_location_url', 
    'date', 'time', 
    'gate_open_time',
    'open_sale_at', 
    'image', 
    'seat_plan',
    'category', 
    'price', 
    'capacity', 
    'available_tickets', 
    'max_tickets_per_user',
    'status', 
    'search_count', 
    'view_count', 
    'last_update_at', 
    'info_image', 
    'info_images', 
    'info_video', 
    'info_caption', 
    'is_tenant_open',
    'tenant_booth_price', 
    'booth_map', 
    'tenant_quota', 
    'map_notice',
    'spotify_playlist_id',
];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
        'open_sale_at' => 'datetime',
        'info_images' => 'array',
        'is_tenant_open' => 'boolean',
    ];

    // ==================== RELASI (PENTING!) ====================

    // Satu Event punya banyak kategori tiket (VIP, Reguler, dll)
    public function ticket_categories()
    {
        return $this->hasMany(TicketCategory::class);
    }

    // Satu Event punya banyak merchandise
    public function merchandises()
    {
        return $this->hasMany(Merchandise::class);
    }

    // Satu Event punya banyak pesanan (Booking)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Satu Event punya banyak update informasi
    public function event_updates()
    {
        return $this->hasMany(EventUpdate::class)->latest();
    }

    // Relasi ke Vendor (Pemilik Event)
    public function vendor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ==================== LOGIC HELPERS ====================

    // Cek apakah event masih bisa dibeli
    public function isAvailable()
    {
        // Cek apakah status active DAN masih ada tiket yang sisa di salah satu kategori
        return $this->status === 'active' && $this->ticket_categories()->sum('quota') > 0;
    }

    public function tenants()
    {
        return $this->hasMany(EventTenant::class);
    }

    // Ambil harga termurah untuk ditampilkan di halaman depan
    public function getStartFromPriceAttribute()
    {
        $minPrice = $this->ticket_categories()->min('price');
        return $minPrice ? 'Rp ' . number_format($minPrice, 0, ',', '.') : 'TBA';
    }
}