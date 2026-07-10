<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$data = Illuminate\Support\Facades\DB::table('attendances')->get();
echo json_encode($data, JSON_PRETTY_PRINT);
