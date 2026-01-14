<?php

use App\Http\Controllers\Tenant\Report\CashCounterReportController;
use App\Http\Controllers\Tenant\Report\SalesReportController;
use App\Http\Controllers\Tenant\Report\SalesReturnReportController;
use App\Http\Controllers\Tenant\Report\TopSellingProductReportController;
use App\Http\Controllers\Tenant\Report\UserReportController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'report'], function () {
    Route::post('top-selling-products-summary', [TopSellingProductReportController::class, 'topSellingProductsSummary'])->name('top.selling.product.summary');
    Route::post('top-selling-products-export', [TopSellingProductReportController::class, 'topSellingProductsExport'])->name('top.selling.product.export');

    Route::any('sales-return-export', [SalesReturnReportController::class, 'exportSalesReturnReport'])->name('sales.return.export.report');

    //User report
    Route::get('user/sales/{user}', [UserReportController::class, 'salesReport'])->name('users.sales_report');
    Route::get('user/purchase/{user}', [UserReportController::class, 'purchaseReport'])->name('users.purchase_report');
    Route::get('user/sales/return/{user}', [UserReportController::class, 'salesReturnReport'])->name('users.sales_return_report');
});

Route::group(['prefix' => 'app'], function () {
    Route::get('export/top-selling-products', [TopSellingProductReportController::class, 'topSellingProductsExport'])
        ->name('top.selling.export');

    Route::get('export/sales', [SalesReportController::class, 'exportSalesReport'])
        ->name('sales.export');

    Route::get('export/users', [UserReportController::class, 'exportSalesReport'])
        ->name('users.export');

    Route::get('export/sales-return', [SalesReturnReportController::class, 'exportSalesReturnReport'])
        ->name('sales_return.export');

    Route::get('export/sales/{skip}', [SalesReportController::class, 'downloadChunkData'])
        ->name('export.sales');

    Route::get('export/cash-counter', [CashCounterReportController::class, 'exportCashCounterReport'])->name('cash_counter.export');

});





