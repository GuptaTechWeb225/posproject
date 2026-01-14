<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pos\Api\Product\Duration\DurationController;

Route::group(['prefix' => 'app'], function () {
     Route::apiResource('duration', DurationController::class);
 });
