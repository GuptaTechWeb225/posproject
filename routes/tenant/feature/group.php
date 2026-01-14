<?php


use App\Http\Controllers\Pos\Api\Product\Products\GroupController;
use App\Http\Controllers\Tenant\Products\GroupExportController;
use App\Http\Controllers\Tenant\Products\GroupImportController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function (Router $route){

    $route->apiResource('groups', GroupController::class);

    Route::get('export/groups', [GroupExportController::class, 'index'])
        ->name('groups.export');

    Route::get('export/groups/{skip}', [GroupExportController::class, 'download'])
        ->name('export.groups');

    $route->post('group/import', [GroupImportController::class, 'preview'])->name('group.import');

    $route->post('group/bulk-import', [GroupImportController::class, 'store'])->name('group.bulk-import');
});