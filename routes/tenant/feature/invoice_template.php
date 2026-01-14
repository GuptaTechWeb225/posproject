<?php

use App\Http\Controllers\Tenant\InvoiceTemplate\InvoiceTemplateController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {

    Route::apiResource('invoice-templates', InvoiceTemplateController::class);

});