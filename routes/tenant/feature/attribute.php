<?php


use App\Http\Controllers\Pos\Api\Product\Products\AttributeController;
use App\Http\Controllers\Tenant\Products\AttributeExportController;
use App\Http\Controllers\Tenant\Products\AttributeImportController;
use App\Http\Controllers\Tenant\Products\AttributeVariationController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function (Router $route) {

    $route->apiResource('attributes', AttributeController::class);

    $route->post('attribute/import', [AttributeImportController::class, 'preview'])->name('attributes.import');

    $route->post('attribute/bulk-import', [AttributeImportController::class, 'store'])->name('attributes.bulk-import');

    $route->get('export/attributes', [AttributeExportController::class, 'index'])
        ->name('attributes.export');

    $route->get('export/attributes/{skip}', [AttributeExportController::class, 'download'])
        ->name('export.attributes');

    $route->post('variation-store', [AttributeVariationController::class, 'store'])->name('attributes.variation-store');

});