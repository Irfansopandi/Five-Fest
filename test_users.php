<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach(App\Models\User::all() as $u) {
    echo "ID: {$u->id} Email: {$u->email} Bookings: " . App\Models\Booking::where('user_id', $u->id)->count() . "\n";
}
