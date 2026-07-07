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
        Schema::table('premium_features', function (Blueprint $table) {
            $table->boolean('has_demo')->default(false)->after('is_unlocked');
            $table->integer('demo_duration_days')->default(1)->after('has_demo');
            $table->timestamp('demo_expires_at')->nullable()->after('demo_duration_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('premium_features', function (Blueprint $table) {
            $table->dropColumn(['has_demo', 'demo_duration_days', 'demo_expires_at']);
        });
    }
};
