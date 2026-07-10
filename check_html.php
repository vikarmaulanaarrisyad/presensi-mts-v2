<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$html = view('monitoring')->render();
$pos = strpos($html, 'firebaseConfig =');
if ($pos !== false) {
    echo substr($html, $pos, 400);
} else {
    echo "Firebase config not found in HTML";
}
