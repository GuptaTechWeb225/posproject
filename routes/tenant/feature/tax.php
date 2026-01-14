<?php

use App\Http\Controllers\Tenant\Tax\TaxController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function (Router $router) {
    $router->apiResource('tax', TaxController::class)->except('index');
});