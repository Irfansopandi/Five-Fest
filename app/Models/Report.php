<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'admin_id',
        'title',
        'file_path',
        'file_name',
        'period_start',
        'period_end',   
        'status',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end'   => 'date',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getPeriodeAttribute()
    {
        return $this->period_start->translatedFormat('d M Y') . ' - ' . $this->period_end->translatedFormat('d M Y');
    }
}