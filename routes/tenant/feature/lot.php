<?php

use App\Http\Controllers\Pos\Api\Inventory\Lot\LotController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

Route::group(['prefix' => 'app'], function (Router $route) {
    $route->apiResource('lot', LotController::class);
});