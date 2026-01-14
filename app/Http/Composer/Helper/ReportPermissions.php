<?php


namespace App\Http\Composer\Helper;


use App\Helpers\Core\Traits\InstanceCreator;

class ReportPermissions
{
    use InstanceCreator;

    public function permissions(): array
    {
        return [
            [
                'name' => __t('sales_report'),
                'url' => url('sales/report'),
                'permission' => authorize_any([
                    'view_sales_report',
                ]),
            ],
            [
                'name' => __t('cash_counter'),
                'url' => url('cash-counter/report'),
                'permission' => authorize_any([
                    'view_cash_counter_report',
                ]),
            ],
            [
                'name' => __t('sales_return_report'),
                'url' => url('sales/return/report'),
                'permission' => authorize_any([
                    'view_sales_return_report',
                ]),
            ],
            [
                'name' => __t('top_selling_products'),
                'url' => url('top-selling-products/report'),
                'permission' => authorize_any([
                    'view_top_selling_product_report',
                ]),
            ],
            [
                'name' => __t('lot_report'),
                'url' => route('support.lot.report.view', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any([
                    'view_lot_report',
                ]),
            ],
            [
                'name' => __t('stock_report'),
                'url' => route('support.stock.report.view', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any([
                    'view_stock_report',
                ]),
            ],
            [
                'name' => __t('warehouse_report'),
                'url' => route('support.branch_warehouse.report.view'),
                'permission' => authorize_any([
                    'view_branch_warehouse_report'
                ]),
            ],
            [
                'name' => __t('profit_loss'),
                'url' => url('profit-loss/report'),
                'permission' => authorize_any([
                    'view_profit_loss_report',
                ]),
            ],
            [
                'name' => __t('user_report'),
                'url' => url('user/report'),
                'permission' => authorize_any([
                    'view_user_report',
                ]),
            ],
            [
                'name' => __t('expense_report'),
                'url' => url('expense/report'),
                'permission' => authorize_any([
                    'view_expense_report',
                ]),
            ],
            [
                'name' => __t('supplier_report'),
                'url' => route('support.supplier.report.view',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_supplier_report'
                ]),
            ],
            [
                'name' => __t('customer_report'),
                'url' => route('support.customer.report.view', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any([
                    'view_customer_report',
                ]),
            ],
        ];
    }

    public function canVisit(): bool
    {
        return authorize_any([
            'view_sales_report','view_cash_counter_report', 'view_top_selling_product', 'view_lot_report', 'view_stock_report',
            'view_branch_warehouse_report','view_user_report', 'view_expense_report', 'view_supplier_report', 'view_customer_report',
            'view_sales_return_report'
        ]);


    }
}
