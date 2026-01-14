<?php

use App\Http\Controllers\Tenant\Branch\BranchController;
use App\Http\Controllers\Tenant\Branch\BranchStatusController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {
    Route::apiResource('branchs', BranchController::class);
    Route::get('branch-status', [BranchController::class, 'branchStatus'])
        ->name('branch.status');

    Route::post('branchs/{branch}/update-status', [BranchStatusController::class, 'update'])
        ->name('branch-status.update');
});