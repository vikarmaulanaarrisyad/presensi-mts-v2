<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change enum to varchar so it can accept 'Izin', 'Sakit', 'Alpa', etc.
        DB::statement("ALTER TABLE attendances MODIFY status VARCHAR(50) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to enum
        DB::statement("ALTER TABLE attendances MODIFY status ENUM('Masuk', 'Pulang', 'Mapel') NOT NULL");
    }
};
