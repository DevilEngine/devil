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
        Schema::create('devilcoin_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('devilcoin_package_id')->constrained()->cascadeOnDelete();

            $table->string('status')->default('pending'); // pending, confirmed, failed, expired
            $table->string('payment_id')->nullable();     // ID retourné par NOWPayments
            $table->string('invoice_url')->nullable();    // Lien direct vers le paiement
            $table->string('pay_currency')->nullable();   // BTC, ETH, XMR...
            $table->string('tx_id')->nullable();          // TX hash (optionnel)

            $table->decimal('price', 16, 8);              // Montant en XMR
            $table->unsignedInteger('amount');            // DevilCoins achetés

            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devilcoin_purchases');
    }
};
