<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pos\Api\Stock\StockAdjustmentController;

Route::group(['prefix' => 'app'], function () {
    Route::apiResource('/stock-adjustments', StockAdjustmentController::class);
});