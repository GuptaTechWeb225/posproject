<?php

use App\Http\Controllers\Common\TestMailController;
use App\Http\Controllers\Core\Auth\UserInvitationController;
use App\Http\Controllers\Core\Notification\NotificationEventController;
use App\Http\Controllers\Tenant\Auth\TenantRoleAPIController;
use App\Http\Controllers\Tenant\Auth\TenantUserAPIController;
use App\Http\Controllers\Tenant\Contacts\CustomerApiController;
use App\Http\Controllers\Tenant\Contacts\CustomerExportController;
use App\Http\Controllers\Tenant\Contacts\SupplierExportController;
use App\Http\Controllers\Tenant\NavigationController;
use App\Http\Controllers\Tenant\Settings\TenantDeliveryController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => ''], function () {

    Route::post('app/test-mail/send', [TestMailController::class, 'sendTestMail'])
        ->name('test-mail.send');

    Route::get('pos-booking-settings', [NavigationController::class, 'posBookingSetting'])
        ->name('tenant.pos-booking-settings');

    Route::get('user/notifications', [NavigationController::class, 'notifications'])
        ->name('tenant.notifications');

    Route::get('settings', [NavigationController::class, 'settings'])
        ->name('tenant.settings');

    Route::get('administration/users', [NavigationController::class, 'users'])
        ->name('tenant.users');

    Route::get('customer/lists', [NavigationController::class, 'customers'])
        ->name('customer.lists');

    Route::get('customer/details/{customer}', [NavigationController::class, 'customerDetails'])
        ->name('customer.details');

    Route::get('customer/import', [NavigationController::class, 'customersImport'])
        ->name('customer.import');

    Route::get('customer/groups', [NavigationController::class, 'customersGroup'])
        ->name('customer.group.lists');

    Route::get('supplier/lists', [NavigationController::class, 'supplier'])
        ->name('supplier.lists');

    Route::get('supplier/details/{supplier}', [NavigationController::class, 'supplierDetails'])
        ->name('supplier.details');

    Route::get('branch/lists', [NavigationController::class, 'branch'])
        ->name('branch.lists');

    Route::get('branch_and_warehouse/lists', [NavigationController::class, 'branchAndWarehouse'])
        ->name('branchAndWarehouse.lists');

    Route::get('supplier/import', [NavigationController::class, 'supplierImport'])
        ->name('supplier.import');

    Route::get('cash/register/lists', [NavigationController::class, 'cashRegister'])
        ->name('cash.register.lists');

    Route::get('selectable/branch', [NavigationController::class, 'selectableBranch'])
        ->name('selectable.branch');

    Route::get('invoice/template/lists', [NavigationController::class, 'invoiceTemplate'])
        ->name('invoice.template.lists');

    Route::get('selectable/invoice-templates', [NavigationController::class, 'selectableInvoiceTemplate'])
        ->name('selectable.invoiceTemplates');

    Route::get('selectable-sales-person', [NavigationController::class, 'selectableSalesPerson'])
        ->name('selectable.sales-person');

    Route::get('notification/events', [NotificationEventController::class, 'index'])
        ->middleware('can:view_notification_settings')
        ->name('notification.event');

    Route::get('selectable/users', [TenantUserAPIController::class, 'index'])
        ->middleware('can:view_users')
        ->name('users.select');

    Route::get('check-mail-settings', [TenantDeliveryController::class, 'isExists'])
        ->name('check-mail-settings');

    Route::get('selectable/roles', [TenantRoleAPIController::class, 'index'])
        ->middleware('can:view_roles')
        ->name('users.roles');

    Route::post('users/invite-user', [UserInvitationController::class, 'invite'])
        ->middleware('can:invite_user')
        ->name('users.invite');

    Route::get('selectable/customer/group', [CustomerApiController::class, 'index'])
        ->name('selectable.customerGroup');

    Route::get('export/customers', [CustomerExportController::class, 'index'])
        ->name('export.customers');

    Route::get('customer/count', [CustomerApiController::class, 'customerCount'])
        ->name('customer.count');

    Route::get('export/sheet/{skip}', [CustomerExportController::class, 'download'])
        ->name('export.sheet');

    Route::get('export/supplier', [SupplierExportController::class, 'index'])
        ->name('export.supplier');

    //Report
    Route::get('sales/report', [NavigationController::class, 'salesReportView'])
        ->name('sales.report.view');

    Route::get('cash-counter/report', [NavigationController::class, 'cashCounterReportView'])
        ->name('cash.counter.report.view');

    Route::get('sales/return/report', [NavigationController::class, 'salesReturnReportView'])
        ->name('sales.return.report.view');

    Route::get('import/product', [NavigationController::class, 'productImport']);

    Route::get('top-selling-products/report', [NavigationController::class, 'topSellingProducts'])
        ->name('top.selling.product.report.view');

    Route::get('expense/report', [NavigationController::class, 'expenseReport'])
        ->name('expense.report.view');

    Route::get('user/report', [NavigationController::class, 'userReport'])
        ->name('user.report.view');

    Route::get('user/report/details/{user}', [NavigationController::class, 'userReportDetails'])
        ->name('user.report.details.view');

    Route::get('branch-warehouse/report', [NavigationController::class, 'branchAndWarehouseReport'])
        ->name('branch_warehouse.report.view');

    Route::get('profit-loss/report', [NavigationController::class, 'profitLossReport'])
        ->name('profit.loss.report.view');


    Route::get('tax-managements', [NavigationController::class, 'taxManagements'])
        ->name('tax-managements');

    Route::get('discounts', [NavigationController::class, 'discounts'])
        ->name('discounts');

    Route::get('counters', [NavigationController::class, 'counters'])
        ->name('counters');

    Route::get('lot/report', [NavigationController::class, 'lot_report'])
        ->name('lot.report.view');

    Route::get('stock/report', [NavigationController::class, 'stock_report'])
        ->name('stock.report.view');

    Route::get('supplier/report', [NavigationController::class, 'supplier_report'])
        ->name('supplier.report.view');

    Route::get('customer/report', [NavigationController::class, 'customer_report'])
        ->name('customer.report.view');
});