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
        Schema::create('swap_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique();
            $table->string('token');
            $table->string('fromCurrency');
            $table->string('toCurrency');
            $table->float('amount_to_receive');
            $table->float('amount_to_send');
            $table->float('expect_amount_to_receive');
            $table->float('expect_amount_to_send');
            $table->float('fee_purcent');
            $table->string('address_receive');
            $table->string('address_send');
            $table->string('refund_address')->nullable();
            $table->string('tx_hash_receive')->nullable();
            $table->string('tx_hash_send')->nullable();
            $table->string('tx_explorer_url_receive')->nullable();
            $table->string('tx_explorer_url_send')->nullable();
            $table->string('status');
            $table->integer('notifier');
            $table->string('qr_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swap_transactions');
    }
};
