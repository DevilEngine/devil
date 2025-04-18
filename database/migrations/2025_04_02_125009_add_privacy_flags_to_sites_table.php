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
        Schema::table('sites', function ($table) {
            $table->boolean('no_kyc')->default(false)->after('status');
            $table->boolean('has_onion')->default(false)->after('no_kyc');
            $table->boolean('decentralized')->default(false)->after('has_onion');
            $table->boolean('smart_contract')->default(false)->after('decentralized');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            //
        });
    }
};
