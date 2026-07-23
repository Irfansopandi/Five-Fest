<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'ENHYPEN WORLD TOUR [FATE PLUS] IN JAKARTA',
                'artist' => 'ENHYPEN',
                'description' => 'Setelah penantian panjang, ENGENE Indonesia akhirnya kembali bersatu dalam gelombang energi luar biasa.',
                'venue' => 'ICE BSD City Hall 1-2',
                'date' => '2026-05-12',
                'time' => '19:00:00',
                'image' => 'events/enha.webp',
                'price' => 2800000,
                'category' => 'K-Pop',
                'capacity' => 10000,
                'available_tickets' => 10000,
                'status' => 'active',
                'vendor_email' => 'mecima@vendor.com',
            ],
            [
                'title' => 'BLACKPINK WORLD TOUR [DEADLINE] IN JAKARTA',
                'artist' => 'BLACKPINK',
                'description' => 'Tur stadion pertama BLACKPINK yang membawa pesan konsentrasi ekstrem dan momen puncak yang tak terlupakan.',
                'venue' => 'Stadion Utama Gelora Bung Karno',
                'date' => '2025-11-01',
                'time' => '18:30:00',
                'image' => 'events/bp.webp',
                'price' => 3800000,
                'category' => 'K-Pop',
                'capacity' => 50000,
                'available_tickets' => 50000,
                'status' => 'active',
                'vendor_email' => 'ime@vendor.com',
            ],
            [
                'title' => 'QnF Karawang: Question and Feel Festival',
                'artist' => 'Various Artists',
                'description' => 'Festival musik kebanggaan Karawang yang menyatukan berbagai genre di area terbuka yang modern.',
                'venue' => 'Summarecon Villagio Outlets Karawang',
                'date' => '2026-08-15',
                'time' => '15:00:00',
                'image' => 'events/1767454003_riizing.png',
                'price' => 150000,
                'category' => 'Indie',
                'capacity' => 3000,
                'available_tickets' => 3000,
                'status' => 'active',
                'vendor_email' => 'groovy@vendor.com',
            ],
            [
                'title' => 'Kanan Kiri Fest 2026',
                'artist' => 'Indie All Stars',
                'description' => 'Rayakan kebebasan berekspresi bersama deretan musisi indie papan atas di kawasan hijau Podomoro.',
                'venue' => 'Podomoro Parkland Karawang',
                'date' => '2026-09-10',
                'time' => '16:00:00',
                'image' => 'events/1764432044_infinite-15th-anniversary-concert.png',
                'price' => 200000,
                'category' => 'Indie',
                'capacity' => 5000,
                'available_tickets' => 5000,
                'status' => 'active',
                'vendor_email' => 'groovy@vendor.com',
            ],
            [
                'title' => 'EXO PLANET #6 - EXOHORIZON IN JAKARTA',
                'artist' => 'EXO',
                'description' => 'Eksplorasi cakrawala baru bersama EXO dalam konser spektakuler dengan visual dan aksi panggung kelas dunia.',
                'venue' => 'Jakarta International Stadium (JIS)',
                'date' => '2026-11-15',
                'time' => '18:30:00',
                'image' => 'events/exo.jpg',
                'price' => 3200000,
                'category' => 'K-Pop',
                'capacity' => 40000,
                'available_tickets' => 40000,
                'status' => 'active',
                'vendor_email' => 'pkent@vendor.com',
            ],
            [
                'title' => 'PESTAPORA 2026',
                'artist' => 'Multi-Genre Artists',
                'description' => 'Perayaan musik lintas generasi terbesar dengan puluhan penampil dan berbagai aktivasi seru dalam satu arena.',
                'venue' => 'Gambir Expo Kemayoran',
                'date' => '2026-09-20',
                'time' => '13:00:00',
                'image' => 'events/deadline.webp',
                'price' => 750000,
                'category' => 'Festival',
                'capacity' => 30000,
                'available_tickets' => 30000,
                'status' => 'active',
                'vendor_email' => 'ckstar@vendor.com',
            ],
            [
                'title' => 'PIANO MONSTER: CHAPTER 2',
                'artist' => 'Various Pianists',
                'description' => 'Pertunjukan piano kolaboratif yang megah, menampilkan aransemen monster dari lagu-lagu populer.',
                'venue' => 'Teater Jakarta, Taman Ismail Marzuki',
                'date' => '2026-10-05',
                'time' => '19:30:00',
                'image' => 'events/lLGRTI4DSddJkA3dETmNSOY60f90mgxlIOBVxE03.jpg',
                'price' => 500000,
                'category' => 'Orchestra',
                'capacity' => 1200,
                'available_tickets' => 1200,
                'status' => 'active',
                'vendor_email' => 'ckstar@vendor.com',
            ],
            [
                'title' => 'KIAS FESTIVAL 2026',
                'artist' => 'For Revenge, Fiersa Besari, DNA, and more',
                'description' => 'Kreativitas dan Inspirasi Anak Sekolah (KIAS) mempersembahkan festival musik spektakuler di Karawang dengan deretan lineup nasional.',
                'venue' => 'Parkland Podomoro Karawang',
                'date' => '2026-07-07',
                'time' => '15:00:00',
                'image' => 'events/kias.jpg',
                'price' => 150000,
                'category' => 'Indie',
                'capacity' => 5000,
                'available_tickets' => 5000,
                'status' => 'active',
                'vendor_email' => 'groovy@vendor.com',
            ],
            [
                'title' => '2025 HEARTS2HEARTS FANMEETING IN JAKARTA',
                'artist' => 'Hearts2House',
                'description' => 'Momen intim dan spesial pertemuan antara Hearts2House dengan para penggemar di Jakarta dalam rangkaian Hearts2House Fanmeeting.',
                'venue' => 'Tennis Indoor Senayan',
                'date' => '2025-05-24',
                'time' => '15:00:00',
                'image' => 'events/h2h.jpg',
                'price' => 1200000,
                'category' => 'K-Pop',
                'capacity' => 3000,
                'available_tickets' => 3000,
                'status' => 'active',
                'vendor_email' => 'dyandra@vendor.com',
            ],
        ];

        foreach ($events as $data) {
            $vendorEmail = $data['vendor_email'];
            unset($data['vendor_email']); // Hapus email agar tidak masuk ke DB event

            $vendor = User::where('email', $vendorEmail)->first();
            
            if ($vendor) {
                Event::updateOrCreate(
                    ['title' => $data['title']],
                    array_merge($data, ['user_id' => $vendor->id])
                );
            }
        }

        $this->command->info("Data Event berhasil disinkronkan ke Vendor masing-masing.");
    }
}