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
            $table->integer('max_demo_requests')->default(1)->after('has_demo');
            $table->integer('demo_used_count')->default(0)->after('max_demo_requests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('premium_features', function (Blueprint $table) {
            $table->dropColumn(['max_demo_requests', 'demo_used_count']);
        });
    }
};
