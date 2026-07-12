<?php

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $status
 * @property \Carbon\Carbon $last_login
 * @property string|null $google_id
 * @property string|null $google_token
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role', // admin,owner, vendor, vendor_staff, tenant, user
        'status',
        'parent_vendor_id',
        'document_type',
        'npwp_number',
        'npwp_name',
        'npwp_address',
        'npwp_file',
        'anggaran_dasar_number',
        'anggaran_dasar_name',
        'anggaran_dasar_address',
        'anggaran_dasar_file',
        'nib_number',
        'verification_status',
        'rejection_reason',
        'verified_at',
        'last_login',
        'google_id',
        'google_token',
        'show_verified_popup',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    // ==================== ROLE CHECKERS ====================

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function isVendor()
    {
        return $this->role === 'vendor';
    }

    public function isVendorStaff()
    {
        return $this->role === 'vendor_staff';
    }

    public function isTenant()
    {
        return $this->role === 'tenant';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    // ==================== RELATIONSHIPS ====================

    // User sebagai pembeli punya banyak booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // User sebagai Vendor punya banyak event
    public function events()
    {
        // FIX: Sesuaikan 'user_id' agar sinkron dengan migration dan controller
        return $this->hasMany(Event::class, 'user_id');
    }


    public function tenantProfile()
    {
        return $this->hasOne(TenantProfile::class);
    }

    public function eventTenants()
    {
        return $this->hasMany(EventTenant::class, 'tenant_id');
    }

    public function withdrawals()
    {
        return $this->hasMany(VendorWithdrawal::class, 'vendor_id');
    }

    public function staff()
    {
        return $this->hasMany(User::class,'parent_vendor_id');
    }

    public function parentVendor()
    {
        return $this->belongsTo(User::class,'parent_vendor_id');
    }
}