<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = [
            [
                'name' => 'Kopi Kenangan',
                'email' => 'kopikenangan@tenant.com',
                'password' => bcrypt('password'),
                'role' => 'tenant',
                'verification_status' => 'verified',
                'profile' => [
                    'business_name' => 'Kopi Kenangan',
                    'category' => 'Food & Beverage',
                    'city' => 'Jakarta',
                    'description' => 'Menjual berbagai macam minuman kopi kekinian.',
                    'portfolio_images' => ['tenants/portfolios/dummy_kopi1.jpg', 'tenants/portfolios/dummy_kopi2.jpg']
                ]
            ],
            [
                'name' => 'Indomie Goreng Pop-Up',
                'email' => 'indomie@tenant.com',
                'password' => bcrypt('password'),
                'role' => 'tenant',
                'verification_status' => 'verified',
                'profile' => [
                    'business_name' => 'Indomie Goreng Pop-Up',
                    'category' => 'Food & Beverage',
                    'city' => 'Bandung',
                    'description' => 'Booth khusus menyajikan variasi indomie kekinian.',
                    'portfolio_images' => ['tenants/portfolios/dummy_indomie1.jpg', 'tenants/portfolios/dummy_indomie2.jpg']
                ]
            ]
        ];

        foreach ($tenants as $tenantData) {
            $profileData = $tenantData['profile'];
            unset($tenantData['profile']);

            $user = \App\Models\User::updateOrCreate(
                ['email' => $tenantData['email']],
                $tenantData
            );

            \App\Models\TenantProfile::updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );
        }
    }
}
