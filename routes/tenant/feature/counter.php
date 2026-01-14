<?php

use App\Http\Controllers\Tenant\Counter\CounterController;
use App\Http\Controllers\Tenant\Counter\CounterStatusController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function (Router $router) {

    $router->apiResource('counters', CounterController::class);

    Route::post('counters/{counter}/update-status', [CounterStatusController::class, 'update'])
        ->name('counter-status.update');

});