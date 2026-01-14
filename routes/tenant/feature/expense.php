<?php

use App\Http\Controllers\Tenant\Expense\ExpenseController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function (Router $route){
    $route->apiResource('expenses', ExpenseController::class);
});