<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pos\Api\Barcode\BarcodeController;

Route::group(['prefix' => 'app'], function () {
    Route::post('inventory/generate-barcode', [BarcodeController::class, 'generateBarCodeForInventory'])
        ->name('barcode_generate');

    Route::get('inventory/get-variant-by-barcode-or-qrcode/{reference_no}', [BarcodeController::class, 'getVariantByBarcodeOrQrCode'])
        ->name('get-variant.barcodeOrQrcode');

    Route::post('inventory/generate-qrcode', [BarcodeController::class, 'generateQRCodeForInventory'])
        ->name('qrcode_generate');
});