<?php

use App\Http\Controllers\Common\Setting\SettingsFormatController;
use App\Http\Controllers\Tenant\Payments\PaypalPaymentController;
use App\Http\Controllers\Tenant\Payments\StripePaymentController;
use Illuminate\Support\Facades\Route;

Route::get('app/settings-format', [SettingsFormatController::class, 'configs'])
    ->name('settings.config');

Route::get('payment-stripe', [StripePaymentController::class, 'handleStripeGet'])->name('stripe');
Route::post('payment-stripe-action', [StripePaymentController::class, 'handleStripePost'])->name('payment.stripe');

Route::get('payment-paypal', [PaypalPaymentController::class, 'handlePaypalGet'])->name('paypal');
Route::get('payment-paypal-execute', [PaypalPaymentController::class, 'paypalExecute'])->name('paypal.execute');
Route::get('payment-paypal-action', [PaypalPaymentController::class, 'handlePaypalAction'])->name('payment.paypal');

