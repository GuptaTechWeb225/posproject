<?php


use App\Http\Controllers\Pos\Api\Product\Products\CategoryController;
use App\Http\Controllers\Tenant\Products\CategoryExportController;
use App\Http\Controllers\Tenant\Products\CategoryImportController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function (Router $route){

    $route->post('categories/bulk-import', [CategoryImportController::class, 'store'])
        ->name('categories.bulk-import');

    $route->post('categories-import', [CategoryImportController::class, 'preview'])
        ->name('categories-import');

    $route->apiResource('categories', CategoryController::class);

    Route::get('export/categories', [CategoryExportController::class, 'index'])
        ->name('categories.export');

    Route::get('export/categories/{skip}', [CategoryExportController::class, 'download'])
        ->name('export.categories');

    $route->patch('category/{category_id}/change-status', [CategoryController::class, 'changeStatus'])
        ->name('status.category.change');

});