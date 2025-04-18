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
        Schema::table('banner_requests', function (Blueprint $table) {
            $table->string('duration')->default('1_week'); // 1_week, 2_weeks, 1_month
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banner_requests', function (Blueprint $table) {
            //
        });
    }
};
