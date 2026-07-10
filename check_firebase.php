<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
try {
    $data = app('firebase.database')->getReference('enroll_responses')->getValue();
    print_r($data);
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
