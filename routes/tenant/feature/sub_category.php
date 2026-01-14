<?php

use App\Http\Controllers\Pos\Api\Product\Products\SubCategoryController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function (Router $route){

    $route->apiResource('sub-categories', SubCategoryController::class);

    $route->patch('sub_category/{sub_category_id}/change-status', [SubCategoryController::class, 'changeStatus'])
        ->name('status.subcategory.change');

});
