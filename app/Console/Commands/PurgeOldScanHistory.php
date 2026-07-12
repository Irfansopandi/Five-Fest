<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;

class PurgeOldScanHistory extends Command
{
    protected $signature = 'scanner:purge-old {--months=6}';
    protected $description = 'Hapus riwayat scan tiket yang sudah lebih dari 6 bulan';

    public function handle()
    {
        $months = $this->option('months');

        $deleted = Ticket::where('status', 'scanned')
            ->where('scanned_at', '<', now()->subMonths($months))
            ->update([
                'status'     => 'active',
                'scanned_at' => null,
                'scanned_by' => null,
            ]);

        $this->info("Berhasil membersihkan {$deleted} riwayat scan.");
    }
}