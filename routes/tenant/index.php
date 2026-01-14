<?php

use App\Http\Controllers\Core\Auth\Role\PermissionController;
use App\Http\Controllers\Tenant\NavigationController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [NavigationController::class, 'dashboard'])
    ->name('tenant.dashboard');

Route::get('permissions', [PermissionController::class, 'index'])
    ->name('permissions')
    ->middleware('can:create_roles');

include __DIR__ . '/appearance.php';


Route::group(['middleware' => 'admin'], function () {
    include_route_files(__DIR__.'/feature/');
});