<?php

use App\Http\Controllers\Tenant\Coupon\CouponController;
use App\Http\Controllers\Tenant\Coupon\DiscountAPIController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function (Router $route){

    $route->apiResource('coupons', CouponController::class);
    $route->apiResource('selectable-discounts', DiscountAPIController::class);

});