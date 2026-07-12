<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'gnuzzzofficial@gmail.com')->first();
if ($user) {
    echo "User ID: " . $user->id . "\n";
    $bookings = App\Models\Booking::where('user_id', $user->id)->get();
    echo "Total bookings: " . $bookings->count() . "\n";
    foreach ($bookings as $b) {
        echo "ID: {$b->id}, Code: {$b->booking_code}, Status: {$b->booking_status}, Payment: {$b->payment_status}, Event ID: {$b->event_id}\n";
    }
} else {
    echo "User not found\n";
}
