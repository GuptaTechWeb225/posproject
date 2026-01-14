<?php

use App\Http\Controllers\Tenant\PaymentMethod\PaymentMethodController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {
    Route::get('payment-method-status', [PaymentMethodController::class, 'paymentMethodStatus'])
        ->name('payment_method.status');
    Route::apiResource('payment-method', PaymentMethodController::class);

});