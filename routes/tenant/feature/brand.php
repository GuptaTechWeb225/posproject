<?php

use App\Http\Controllers\Pos\Api\Product\Products\BrandController;
use App\Http\Controllers\Tenant\Products\BrandExportController;
use App\Http\Controllers\Tenant\Products\BrandImportController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function (Router $route){

    $route->apiResource('brands', BrandController::class);

    $route->post('brand/bulk-import', [BrandImportController::class, 'store'])->name('brand.bulk-import');

    $route->get('export/brands', [BrandExportController::class, 'index'])
        ->name('brands.export');

    $route->get('export/brands/{skip}', [BrandExportController::class, 'download'])
        ->name('export.brands');

});