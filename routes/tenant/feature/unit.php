<?php

use App\Http\Controllers\Pos\Api\Product\Products\UnitController;
use App\Http\Controllers\Tenant\Products\UnitExportController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function (Router $route) {

    Route::apiResource('unit', UnitController::class);

    $route->get('export/units', [UnitExportController::class, 'index'])
        ->name('units.export');

    $route->get('export/units/{skip}', [UnitExportController::class, 'download'])
        ->name('export.units');

    $route->patch('unit/{unit_id}/change-status', [UnitController::class, 'changeStatus'])
        ->name('status.unit.change');

});