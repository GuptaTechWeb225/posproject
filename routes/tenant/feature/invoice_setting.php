<?php

use App\Http\Controllers\Pos\Api\Settings\InvoiceSettingController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {

    Route::post('invoice-settings', [InvoiceSettingController::class, 'store'])
        ->name('settings.invoice-settings');
});