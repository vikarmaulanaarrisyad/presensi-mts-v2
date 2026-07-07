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
        Schema::create('attendance_schedules', function (Blueprint $table) {
            $table->id();
            $table->time('start_masuk');
            $table->time('end_masuk');
            $table->time('batas_terlambat');
            $table->time('start_pulang');
            $table->time('end_pulang');
            $table->timestamps();
        });

        // Insert default schedule
        \Illuminate\Support\Facades\DB::table('attendance_schedules')->insert([
            'start_masuk' => '06:00:00',
            'end_masuk' => '08:00:00',
            'batas_terlambat' => '07:00:00',
            'start_pulang' => '14:00:00',
            'end_pulang' => '16:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_schedules');
    }
};
