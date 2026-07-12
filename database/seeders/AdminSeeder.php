<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Admin ConcertHub',
                'email'    => 'admin@concerthub.com',
                'phone'    => '081234567890',
                'password' => Hash::make('admin123'),
                'role'     => 'admin'
            ],
            
            [
                'name'      => 'Owner FiveFest',
                'email'     =>'owner@fivefest.com',
                'phone'     =>'0858645326645',
                'password'  =>Hash::make('owner1234'),
                'role'      => 'owner'
            ],

            [
                'name' => 'Tarisa Uswa Hazani',
                'email' => 'tarisaisa@gmail.com',
                'phone' => '08987654321',
                'password' => Hash::make('tarisa123'),
                'role' => 'user'
            ],
            [
                'name' => 'User ConcertHub',
                'email' => 'user@concerthub.com',
                'phone' => '081298765432',
                'password' => Hash::make('user123'),
                'role' => 'user'
            ],
            [
                'name'     => 'Groovy Vendor',
                'email'    => 'groovy@vendor.com',
                'phone'    => '081234567891',
                'password' => Hash::make('groovy123'),
                'role'     => 'vendor',
                'verification_status' => 'verified',
            ],
            [
                'name' => 'PK Entertainment',
                'email' => 'pkent@vendor.com',
                'phone' => '081122334455',
                'password' => Hash::make('pkent123'),
                'role' => 'vendor',
                'verification_status' => 'verified',
            ],
            [
                'name' => 'Dyandra Global',
                'email' => 'dyandra@vendor.com',
                'phone' => '089988776655',
                'password' => Hash::make('dyandra123'),
                'role' => 'vendor',
                'verification_status' => 'verified',
            ],
            [
                'name' => 'Mecima Pro',
                'email' => 'mecima@vendor.com',
                'phone' => '087766554433',
                'password' => Hash::make('mecima123'),
                'role' => 'vendor',
                'verification_status' => 'verified',
            ],
            [
                'name' => 'Ime Indonesia',
                'email' => 'ime@vendor.com',
                'phone' => '081122334455',
                'password' => Hash::make('ime123'),
                'role' => 'vendor',
                'verification_status' => 'verified',
            ],
            [
                'name' => 'CK Star Entertaiment',
                'email' => 'ckstar@vendor.com',
                'phone' => '081133557799',
                'password' => Hash::make('ckstar123'),
                'role' => 'vendor',
                'verification_status' => 'verified',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}