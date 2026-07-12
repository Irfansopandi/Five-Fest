<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use SimpleSoftwareIO\QrCode\Facades\QrCode;

try {
    $png = QrCode::format('png')->size(300)->generate('TC-123456');
    echo "PNG generated successfully! Size: " . strlen($png) . " bytes\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
