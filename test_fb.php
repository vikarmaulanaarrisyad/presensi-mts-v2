<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
try {
    app('firebase.database')->getReference('test')->set('ok');
    echo 'Firebase OK';
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
