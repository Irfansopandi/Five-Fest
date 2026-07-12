<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class DeactivatePastEvents extends Command
{
    protected $signature = 'events:deactivate-past';

    protected $description = 'Menonaktifkan otomatis semua event yang tanggalnya sudah lewat';

    public function handle()
    {
        $count = Event::whereDate('date', '<', now()->toDateString())
            ->where('status', '!=', 'inactive')
            ->update(['status' => 'inactive']);

        $this->info("Berhasil menonaktifkan {$count} event yang sudah lewat.");
    }
}