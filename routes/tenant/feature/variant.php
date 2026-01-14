<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\Products\VariantController;

Route::group(['prefix' => 'app'], function () {
    Route::apiResource('variant', VariantController::class)->except(['show']); // show is from web.php

    Route::get('variant/product/{product_id}', [VariantController::class, 'variantList'])
        ->name('variant.list');

    Route::patch('variant/{variant}/status-update', [VariantController::class, 'changeVariantStatus'])
        ->name('variant-status.update');

    Route::post('variant/{variant}/make-default', [VariantController::class, 'makeDefault'])
        ->name('variant.make-default');



});

