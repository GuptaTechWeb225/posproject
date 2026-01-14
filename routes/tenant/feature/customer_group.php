<?php

use App\Http\Controllers\Tenant\Contacts\CustomerGroupController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {

    Route::apiResource('customer-groups', CustomerGroupController::class);

});