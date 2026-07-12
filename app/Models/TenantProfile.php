<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city',
        'business_name',
        'category',
        'description',
        'portfolio_images'
    ];

    protected $casts = [
        'portfolio_images' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
