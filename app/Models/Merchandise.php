<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{
    use HasFactory;

    // Pastikan nama tabelnya sesuai, biasanya Laravel otomatis cari 'merchandises'
    protected $table = 'merchandises';

    protected $fillable = [
        'event_id',
        'name',
        'price',
        'stock',
        'sizes',
        'description',
        'image'
    ];

    /**
     * Relasi: Satu merchandise itu milik satu Event tertentu
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}