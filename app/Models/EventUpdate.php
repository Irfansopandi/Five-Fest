<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventUpdate extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'images', 'video', 'caption'];

    protected $casts = [
        'images' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
