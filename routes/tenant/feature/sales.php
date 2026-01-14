<?php

use App\Http\Controllers\Tenant\Invoice\InvoiceController;
use App\Http\Controllers\Tenant\Order\OrderController;
use App\Http\Controllers\Tenant\Sales\SaleReturn\SaleReturnController;
use App\Http\Controllers\Tenant\Sales\SaleServiceController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {
    //Discount routes
    Route::get('invoice-info', [SaleServiceController::class, 'invoiceBasicInfo'])->name('invoice.info');

    //Order routes
    Route::post('order-payment/{order}', [OrderController::class, 'orderPayment'])->name('order.payment');
    Route::get('cancel-order/{order}', [OrderController::class, 'cancelOrder'])->name('order.cancel');
    Route::post('hold-order-delete', [OrderController::class, 'holdOrderDelete'])->name('order.hold.delete');
    Route::get('add-new-product/{order}', [OrderController::class, 'addNewProduct'])->name('order.new.add');

    //Invoice routes
    Route::get('invoice-list', [InvoiceController::class, 'index'])->name('invoice.list');
    Route::get('invoice-view/{invoice}', [InvoiceController::class, 'invoiceView'])->name('invoice.view');
    Route::get('due-payment-info/{order}', [OrderController::class, 'duePaymentInfo'])->name('due.info');
    Route::post('due-payment-receive/{order}', [OrderController::class, 'dueReceive'])->name('due.receive');

    //Return order routes
    Route::get('generate-return-invoice/{order}', [SaleReturnController::class, 'generateInvoice'])->name('generate.invoice');
    Route::get('generate-sales-invoice/{order}', [OrderController::class, 'generateInvoice'])->name('generate.sales-invoice');

});
