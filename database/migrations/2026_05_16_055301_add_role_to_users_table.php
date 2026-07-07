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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom baru untuk mendeteksi aktor/jabatan
            $table->string('role')->default('murid')->after('email'); 
            
            // Menambahkan kolom nomor WhatsApp Orang Tua jika belum ada
            $table->string('nomor_wa_ortu')->nullable()->after('role');
            
            // Menambahkan kolom Chat ID Telegram Orang Tua jika belum ada
            $table->string('telegram_id_ortu')->nullable()->after('nomor_wa_ortu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kembali kolom jika migrasi dibatalkan (rollback)
            $table->dropColumn(['role', 'nomor_wa_ortu', 'telegram_id_ortu']);
        });
    }
};