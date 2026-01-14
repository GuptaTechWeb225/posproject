<?php

use App\Http\Controllers\Tenant\Expense\ExpenseAreaApiController;
use App\Http\Controllers\Tenant\NavigationController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => ''], function () {

    Route::get('pos-settings', [NavigationController::class, 'posSetting'])
        ->name('tenant.pos-settings');


    /*Product*/
    Route::get('category/lists', [NavigationController::class, 'category'])
        ->name('category.lists');

    Route::get('category/import', [NavigationController::class, 'categoryImport'])
        ->name('category.import');

    Route::get('unit/lists', [NavigationController::class, 'unit'])
        ->name('unit.lists');

    Route::get('attributes', [NavigationController::class, 'attribute'])
        ->name('attributes');

    Route::get('attribute/import', [NavigationController::class, 'attributeImport'])
        ->name('attribute.import');

    Route::get('group/lists', [NavigationController::class, 'group'])
        ->name('group.lists');

    Route::get('product/group/import', [NavigationController::class, 'productGroupImport'])
        ->name('product.group.import');

    Route::get('brands', [NavigationController::class, 'brand'])
        ->name('brands');

    Route::get('brand/import', [NavigationController::class, 'importBrand'])
        ->name('brand.import');

    Route::get('products/list', [NavigationController::class, 'product'])
        ->name('products.list');

    Route::get('products/create', [NavigationController::class, 'addProduct'])
        ->name('products.create');

    Route::get('products/edit/{id}', [NavigationController::class, 'editProduct'])
        ->name('products.edit');

    /*Warehouse*/
    Route::get('warehouse/stock', [NavigationController::class, 'warehouseStock'])
        ->name('warehouse.stock');

    Route::get('Lot/list', [NavigationController::class, 'lotList'])
        ->name('Lot.list');

    Route::get('stocks/adjustment', [NavigationController::class, 'stockAdjustment'])
        ->name('stocks.adjustment');

    Route::get('stocks/import', [NavigationController::class, 'stocksImport'])
        ->name('stocks.import');

    Route::get('stock/transfer', [NavigationController::class, 'stockTransfer'])
        ->name('stock.transfer');

    Route::get('warehouse/list', [NavigationController::class, 'warehouseList'])
        ->name('warehouse.list');

    /*Inventory*/
    Route::get('receive/product', [NavigationController::class, 'receiveProduct'])
        ->name('receive.product');

    Route::get('branch/stock', [NavigationController::class, 'branchStock'])
        ->name('branch.stock');

    Route::get('internal/transfer', [NavigationController::class, 'internalTransfer'])
         ->name('internal.transfer');

    /*Sales*/
    Route::get('sales/view', [NavigationController::class, 'salesView'])
        ->name('sales.view');

    Route::get('returns', [NavigationController::class, 'returns'])
        ->name('returns');

    Route::get('invoice', [NavigationController::class, 'invoice'])
        ->name('invoice');

    Route::get('cash/counter', [NavigationController::class, 'cashCounter'])
        ->name('cash.counter');

    Route::get('expense/lists', [NavigationController::class, 'expense'])
        ->name('expense.lists');

    Route::get('expense/area-of-expense', [NavigationController::class, 'areaOfExpense'])
        ->name('expense.area-of-expense');

    Route::get('tax-managements', [NavigationController::class, 'taxManagements'])
        ->name('tax-managements');

    Route::get('reports', [NavigationController::class, 'reports'])
        ->name('reports');

    Route::get('sub-category/lists', [NavigationController::class, 'subCategory'])
        ->name('sub-category.list');

    Route::get('selectable/expense-areas', [ExpenseAreaApiController::class, 'index'])
        ->name('selectable.expense-areas');

    // Product Details
    Route::get('products/{product}', [NavigationController::class, 'productDetails'])
        ->name('product.details');

    //Inventory
    Route::get('inventory/stock', [NavigationController::class, 'stockView'])
        ->name('inventory.stock');

    Route::get('inventory/stock/overview/variant/{variant_id}', [NavigationController::class, 'stockOverView'])
        ->name('inventory.stock-overview');

    Route::get('lot/add', [NavigationController::class, 'addLot'])
        ->name('add.lot');

    Route::get('lot/edit/{lot_id}', [NavigationController::class, 'editLot'])
        ->name('lot.edit');

    Route::get('manage/lot', [NavigationController::class, 'lotView'])
        ->name('manage.lot');

    Route::get('stock/adjustment', [NavigationController::class, 'adjustmentView'])
        ->name('stock.adjustment');

    Route::get('import/stock', [NavigationController::class, 'importStockView'])
        ->name('import.stock');

    Route::get('print/barcode', [NavigationController::class, 'printBarcodeView'])
        ->name('print.barcode');

    Route::get('print/qrcode', [NavigationController::class, 'printQRcodeView'])
        ->name('print.qrcode');

    Route::get('internal/transfer', [NavigationController::class, 'internalTransferView'])
        ->name('internal.transfer');
});
