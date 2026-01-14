<?php

namespace Database\Seeders\App\Traits;

trait ReportsPermissionTrait
{
    public function reports($type, $group = null): array
    {
        return [
            [
                'name' => 'view_sales_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_cash_counter_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_sales_return_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_top_selling_product_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_lot_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_stock_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_branch_warehouse_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_profit_loss_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_user_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_expense_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_supplier_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_customer_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'export_sales',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'export_cash_counter',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'export_sales_return',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'export_users',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'sales_report_users',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'purchase_report_users',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'sales_return_report_users',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
        ];
    }
}