<?php

use App\Http\Controllers\Pos\Api\Inventory\BranchAndWarehouseController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {
    Route::apiResource('branch_or_warehouses', BranchAndWarehouseController::class)->except('index', 'show');

    Route::patch('branch_or_warehouse/{id}/update-status', [BranchAndWarehouseController::class, 'branchOrWarehouseStatusChange'])
        ->name('branch-or-warehouse-status.update');
});