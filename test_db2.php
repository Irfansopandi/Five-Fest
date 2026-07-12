<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$userId = 13;
$query = App\Models\Booking::with('event')->where('user_id', $userId);
echo "SQL: " . $query->toSql() . "\n";
echo "Count: " . $query->count() . "\n";
$bookings = $query->paginate(10);
echo "Paginated total: " . $bookings->total() . "\n";

foreach($bookings as $b) {
    echo "- Booking {$b->id} : event is " . ($b->event ? "found" : "NULL") . "\n";
}
