<?php

use App\Http\Controllers\Tenant\Contacts\SupplierController;
use App\Http\Controllers\Tenant\Contacts\SupplierDetailsController;
use App\Http\Controllers\Tenant\Contacts\SupplierImportController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function (Router $route) {

    $route->post('supplier/balk-import', [SupplierImportController::class, 'store'])
        ->name('supplier.balk-import');

    $route->post('supplier-import', [SupplierImportController::class, 'preview'])
        ->name('supplier-import');

    $route->apiResource('suppliers', SupplierController::class);

    $route->patch('supplier/{supplier}/change-status', [SupplierController::class, 'supplierStatusChange'])
        ->name('supplier.change-status');

    $route->post('supplier/profile/picture/{supplier}', [SupplierDetailsController::class, 'profilePictureUpload'])
        ->name('supplier.profile.upload');
});
