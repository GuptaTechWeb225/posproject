<?php

use App\Http\Controllers\Pos\Api\Stock\StockController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {
    Route::apiResource('stock', StockController::class);
    Route::get('stock-overview/variant/{variant_id}', [StockController::class,'stockOverView'])->name('stock_overview.view');
});
