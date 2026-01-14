<?php

use App\Http\Controllers\Tenant\Selectable\SelectableController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'app'], function (Router $route) {
    $route->get('selectable/branches', [SelectableController::class,'selectableBranches']);
    $route->get('selectable/managers', [SelectableController::class,'selectableManages']);
    $route->get('selectable/users', [SelectableController::class,'selectableUsers']);
    $route->get('selectable-suppliers', [SelectableController::class,'selectableSuppliers']);
    $route->get('selectable/received-bys', [SelectableController::class,'selectableReceivedBys']);
    $route->get('selectable-units', [SelectableController::class, 'selectableUnits'])->name('selectable.units');
    $route->get('selectable-brands', [SelectableController::class, 'selectableBrands'])->name('selectable.brands');
    $route->get('selectable-categories', [SelectableController::class, 'selectableCategories'])->name('selectable.categories');
    $route->get('selectable-sub-categories/{category_id}', [SelectableController::class, 'selectableSubCategories'])->name('selectable.sub_categories');
    $route->get('selectable-groups', [SelectableController::class, 'selectableGroups'])->name('selectable.groups');
    $route->get('selectable-tags', [SelectableController::class, 'selectableTags'])->name('selectable.tags');
    $route->get('selectable-tax', [SelectableController::class, 'selectableTax'])->name('selectable.tax');
    $route->get('selectable-variations', [SelectableController::class, 'selectableVariations'])->name('selectable.variations');
    $route->get('selectable-attributes', [SelectableController::class, 'selectableAttributes'])->name('selectable.attributes');
    $route->get('selectable-products', [SelectableController::class, 'selectableProducts'])->name('selectable.products');
    $route->get('selectable-variants', [SelectableController::class, 'selectableVariants'])->name('selectable.variants');

    $route->get('selectable-stock-adjustment-variants', [SelectableController::class, 'selectableVariantsForStockAdjustment'])
        ->name('selectable.variants_for_stock_adjustment');


    $route->get('selectable-warehouses', [SelectableController::class, 'selectableWarehouses'])->name('selectable.warehouses');
    $route->get('selectable-statuses', [SelectableController::class, 'selectableStatuses'])->name('selectable.statuses');
    $route->get('selectable-durations',[SelectableController::class, 'selectableDurations'])->name('selectable.durations');
    $route->get('selectable-purchase-status',[SelectableController::class, 'selectablePurchaseStatus'])->name('selectable.purchase_status');

    $route->get('selectable-internal-transfer-status',[SelectableController::class, 'selectableInternalTransferStatus'])->name('selectable.internal_transfer_status');

    $route->get('selectable-branches-or-warehouses',[SelectableController::class, 'selectableBranchesOrWarehouses'])->name('selectable.branches_or_warehouses');
    $route->get('selectable-customer-groups',[SelectableController::class, 'selectableCustomerGroups'])
        ->name('selectable.customer_groups');
    $route->get('selectable-countries',[SelectableController::class, 'selectableCountries'])->name('selectable.countries');
    $route->get('selectable-customers',[SelectableController::class, 'selectableCustomers'])->name('selectable.customers');
    $route->get('selectable-cash-register',[SelectableController::class, 'selectableCashRegister'])->name('selectable.cash_register');
    $route->get('selectable-discount-product',[SelectableController::class, 'selectableDiscountProduct'])->name('selectable.discount_product');

    $route->get('selectable-lot',[SelectableController::class, 'selectableLot'])->name('selectable.lot');
    $route->get('selectable-lot-variants',[SelectableController::class, 'selectableLotVariants'])->name('selectable.lot-variants');

    $route->get('selectable-invoice',[SelectableController::class, 'selectableInvoice'])->name('selectable.invoice');
    $route->get('selectable-year',[SelectableController::class, 'selectableYear'])->name('selectable.year');
    $route->get('get-tax', [SelectableController::class, 'getTax'])->name('get.all.tax');


});