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
            $table->boolean('demo_requested')->default(false)->after('demo_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('premium_features', function (Blueprint $table) {
            $table->dropColumn('demo_requested');
        });
    }
};
