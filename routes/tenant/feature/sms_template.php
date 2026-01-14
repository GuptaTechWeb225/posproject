<?php

use App\Http\Controllers\Tenant\Settings\SmsTemplateController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {

    Route::apiResource('sms-templates', SmsTemplateController::class);

});