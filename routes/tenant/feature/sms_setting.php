<?php

use App\Http\Controllers\Tenant\Settings\SmsSettingController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {

    Route::post('sms-settings', [SmsSettingController::class, 'update'])
        ->name('settings.sms-settings');

    Route::get('sms-setting-info',[SmsSettingController::class,'getData'])->name('settings.sms-setting-info');

});