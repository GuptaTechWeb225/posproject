<?php

use App\Http\Controllers\Tenant\Sales\SaleServiceController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {
    //cash counter open close status
    Route::get('cash-counter-open-close-status', [SaleServiceController::class, 'cashCounterOpenCloseStatus'])->name('cash_counter.open_close_status');
});
