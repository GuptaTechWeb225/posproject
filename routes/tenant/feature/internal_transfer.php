<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pos\Api\Stock\InternalTransferController;

Route::group(['prefix' => 'app'], function (){
    Route::apiResource('internal-transfers', InternalTransferController::class);
    Route::post('change-internal-transfer-status/{id}', [InternalTransferController::class,'changeInternalTransferStatus'])->name('internal_transfer_status.change');
});