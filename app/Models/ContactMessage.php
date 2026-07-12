<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'photo_path',
        'status',
        'replied_at',
        'admin_notes'
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function isRead()
    {
        return $this->status === 'read';
    }

    public function markAsRead()
    {
        $this->update(['status' => 'read']);
    }
}
