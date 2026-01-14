<?php

use App\Http\Controllers\Pos\Api\Barcode\BarcodeController;
use App\Http\Controllers\Pos\Api\Inventory\BranchAndWarehouseController;
use App\Http\Controllers\Pos\Api\Inventory\Lot\LotController;
use App\Http\Controllers\Pos\Api\Product\Export\ProductExportController;
use App\Http\Controllers\Pos\Api\Report\CustomerReportController;
use App\Http\Controllers\Pos\Api\Report\LotReportController;
use App\Http\Controllers\Pos\Api\Report\ProductStockReportController;
use App\Http\Controllers\Pos\Api\Report\ProductStockReportExportController;
use App\Http\Controllers\Pos\Api\Report\SupplierReportController;
use App\Http\Controllers\Pos\Api\Settings\InvoiceSettingController;
use App\Http\Controllers\Pos\Api\Stock\StockController;
use App\Http\Controllers\Tenant\Contacts\AddressController;
use App\Http\Controllers\Tenant\Contacts\CustomerDetailsController;
use App\Http\Controllers\Tenant\Dashboard\DashboardController;
use App\Http\Controllers\Tenant\Expense\ExpenseController;
use App\Http\Controllers\Tenant\Invoice\InvoiceController;
use App\Http\Controllers\Tenant\NavigationController;
use App\Http\Controllers\Tenant\Order\OrderController;
use App\Http\Controllers\Tenant\Products\VariantController;
use App\Http\Controllers\Tenant\Report\BranchWarehouseReportController;
use App\Http\Controllers\Tenant\Report\CashCounterReportController;
use App\Http\Controllers\Tenant\Report\ExpenseReportController;
use App\Http\Controllers\Tenant\Report\ProfitLossReportController;
use App\Http\Controllers\Tenant\Report\SalesReportController;
use App\Http\Controllers\Tenant\Report\SalesReturnReportController;
use App\Http\Controllers\Tenant\Report\TopSellingProductReportController;
use App\Http\Controllers\Tenant\Report\UserReportController;
use App\Http\Controllers\Tenant\Sales\SaleReturn\SaleReturnController;
use App\Http\Controllers\Tenant\Sales\SaleServiceController;
use App\Http\Controllers\Tenant\Tax\TaxController;
use Illuminate\Support\Facades\Route;

//================================================================================
//01. Dashboard route..only logged user will be able to get response
//================================================================================
Route::group(['prefix' => 'dashboard'], function () {
    Route::get('highlights',[DashboardController::class,'highlights'])->name('dashboard.highlights');
    Route::get('recent-sales',[DashboardController::class,'recentSales'])->name('dashboard.recent_sales');
    Route::get('top-selling-products',[DashboardController::class,'topSellingProducts'])->name('dashboard.top_selling_products');
    Route::get('top-customers',[DashboardController::class,'topCustomers'])->name('dashboard.top_customer');
    Route::get('stock-summary',[DashboardController::class,'stockSummary'])->name('dashboard.stock_summary');
    Route::get('purchase-sale-statistics',[DashboardController::class,'purchaseSaleStatistics'])->name('dashboard.purchase_status');
});


//================================================================================
//02. Reports route..only logged user will be able to get response
//================================================================================
Route::group(['prefix' => 'app'], function () {
    Route::get('customer/orders/{customer}', [CustomerDetailsController::class, 'customerOrders'])->name('customer.order');
    Route::get('/tax', [TaxController::class, 'index']);
    Route::get('/check-branch-warehouse-active', [BranchAndWarehouseController::class,'cheBranchWarehouseActive']);

    Route::patch('/manage-users/branch_or_warehouse/{branch_or_warehouse_id}', [BranchAndWarehouseController::class,'manageBranchOrWarehouseUsers'])
        ->name('branch-warehouse-users.manage');

    Route::get('order-return-view/{order}', [InvoiceController::class, 'returnInvoiceView'])->name('order.return.view');

    //change lot status without permission
    Route::post('change-lot-status/{id}', [LotController::class,'changeLotStatus'])->name('lot-status.change');

    //exports products without permission
    Route::get('export/product/all',[ProductExportController::class,'exportsAllProducts']);

    Route::get('get-general-settings',[InvoiceSettingController::class,'getInvoiceSetting'])->name('get.general.settings');

    //SaleServiceController
    Route::get('discount-info', [SaleServiceController::class, 'getFlatDiscount'])->name('discount.info');

    //StockController
    Route::get('get-available-stock/variant/{variant_id}/branch_or_warehouse/{branch_or_warehouse_id}', [StockController::class,'getAvailableStock']);

    //BarcodeController
    Route::get('product/get_upc',[BarcodeController::class, 'generateBarcodeNumber']);
    Route::get('product/check_unique_upc/{upc}/{variant_id?}',[BarcodeController::class, 'UniqueUpcCheck']);

    //VariantController
    Route::get('variant/{variant}', [VariantController::class, 'show']); // used for stock adjustment add
    Route::get('variant-product-tax-and-discount/{branch}/{variant}', [VariantController::class, 'getVariantProductTax'])
        ->name('variant.tax.discount');

    //AddressController
    Route::get('address-areas', [AddressController::class, 'addressArea'])->name('address.areas');
    Route::get('address-city', [AddressController::class, 'addressCity'])->name('address.city');
    Route::get('address/{customer}', [AddressController::class ,'customerById'])->name('customerBy.id');

    //BranchWarehouseReportController
    Route::get('export/branch-warehouse', [BranchWarehouseReportController::class, 'exportBranchOrWarehouseReport'])->name('branchWarehouse.export');
    Route::get('branch_or_warehouses', [BranchAndWarehouseController::class, 'index'])->name('branch_or_warehouses.index');
    Route::get('branch_or_warehouses/{branch_or_warehouse}', [BranchAndWarehouseController::class, 'show'])->name( 'branch_or_warehouses.show');
    Route::get('customer/details/{customer}', [CustomerDetailsController::class, 'index'])->name('customer.details');

    //OrderController
    Route::post('order-hold', [OrderController::class, 'holdOrderStore'])->name('order.hold');
    Route::get('on-hold-orders', [OrderController::class, 'onHoldOrders'])->name('order.on.hold');
    Route::post('order-store', [OrderController::class, 'store'])->name('order.add');
    Route::get('order-max-min-price', [OrderController::class, 'maxMinPrice'])->name('order.max.min.price');

    //SaleServiceController
    Route::post('cash-counter-open-close', [SaleServiceController::class, 'cashCounterOpenClose'])->name('cash_counter.open_close');
    Route::get('sale/products', [SaleServiceController::class, 'productList'])->name('sales.products');
    Route::get('cash-counter-information/{cashRegister}', [SaleServiceController::class, 'cashRegisterInformation'])->name('cashCounterInfo');
    Route::get('payment-methods', [SaleServiceController::class, 'paymentMethods'])->name('payment.methods')->name('payment.methods');

    //SaleReturnController
    Route::get('sale-return-view/{returnOrder}', [SaleReturnController::class, 'returnOrderView'])->name('sale.return.view');
    Route::get('return-order-list', [SaleReturnController::class, 'index'])->name('return.order.list');
    Route::get('return-order-max-min-price', [SaleReturnController::class, 'maxMinPrice'])->name('return.max.min.price');
    Route::post('store-return-order', [SaleReturnController::class, 'store'])->name('return.order.store');

    //ProfitLossReportController
    Route::get('export/profit-loss', [ProfitLossReportController::class, 'exportProfitLoss'])->name('profit.loss.export');

    //ExpenseController
    Route::delete('/expense-attachment/delete/{id}',[ExpenseController::class,'deleteFile'])->name('delete.attachment');

    Route::get('supplier-all-address/{supplier}', [AddressController::class, 'supplierAddresses'])->name('supplier.addresses');
});


//================================================================================
//03. Reports route..only logged user will be able to get response
//================================================================================
Route::group(['prefix' => 'report'], function () {
    //SalesReportController
    Route::get('sales-summary', [SalesReportController::class, 'salesSummary'])->name('sales.summary');
    Route::get('sales', [SalesReportController::class, 'index'])->name('sales.report');
    Route::any('sales-export', [SalesReportController::class, 'exportSalesReport'])->name('sales.export.report');

    //CashCounterReportController
    Route::get('cash-counter', [CashCounterReportController::class, 'index'])->name('cash.counter.index');

    //SalesReturnReportController
    Route::get('sales-return-summary', [SalesReturnReportController::class, 'salesReturnSummary'])->name('sales.return.summary');
    Route::get('sales-return', [SalesReturnReportController::class, 'index'])->name('sales.return.report');

    //TopSellingProductReportController
    Route::get('top-selling-products', [TopSellingProductReportController::class, 'index'])->name('top.selling.product.index');

    //LotReportController
    Route::get('purchase-summary', [LotReportController::class, 'purchaseSummary'])->name('purchase.summary');
    Route::get('purchase-export', [LotReportController::class, 'exportAllLotByBranchOrWarehouse'])->name('purchase_report.export');
    Route::get('purchase', [LotReportController::class, 'index'])->name('purchase.report');

    //ProductStockReportController
    Route::get('product-stock', [ProductStockReportController::class, 'index'])->name('product-stock.report');

    //ProductStockReportExportController
    Route::get('product-stock-export', [ProductStockReportExportController::class, 'exportAllByBranchOrWarehouse'])->name('product-stock.export');

    //BranchWarehouseReportController
    Route::get('branch-warehouse', [BranchWarehouseReportController::class, 'index'])->name('branchWarehouse.report.index');

    //ProfitLossReportController
    Route::get('profit-loss', [ProfitLossReportController::class, 'index'])->name('profit.loss.report.index');

    //UserReportController
    Route::get('users', [UserReportController::class, 'index'])->name('users.report.index');

    //ExpenseReportController
    Route::get('expense', [ExpenseReportController::class, 'index'])->name('expense.report.index');

    //SupplierReportController
    Route::get('supplier', [SupplierReportController::class, 'index'])->name('supplier.report');
    Route::get('supplier-export', [SupplierReportController::class, 'exportAllSupplierByBranchOrWarehouse'])->name('supplier_report.export');

    //CustomerReportController
    Route::get('customer', [CustomerReportController::class, 'index'])->name('customer.report');
    Route::get('customer-export', [CustomerReportController::class, 'exportAllCustomerByBranchOrWarehouse'])->name('customer_report.export');
});

Route::get('user/my-profile', [NavigationController::class, 'profile'])->name('user.profile');