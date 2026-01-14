<?php

use App\Http\Controllers\Tenant\Order\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {
    Route::apiResource('order', OrderController::class)->except(['store']);
});