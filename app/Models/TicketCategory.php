<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model 
{
    // Pastikan 'benefits' ada di sini karena kamu memakainya di form
    protected $fillable = ['event_id', 'name', 'price', 'quota', 'benefits'];

    // Relasi ke Event (Opsional tapi disarankan)
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}