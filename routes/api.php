<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\NowPaymentsWebhookController;

Route::post('/nowpayments/webhook', [NowPaymentsWebhookController::class, 'handle'])->name('nowpayments.webhook');
