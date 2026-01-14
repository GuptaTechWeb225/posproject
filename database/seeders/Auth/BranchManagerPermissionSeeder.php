<?php

namespace Database\Seeders\Auth;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class BranchManagerPermissionSeeder extends Seeder
{
    use DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        $branch_manager = Role::query()
            ->where('name', config('access.users.app_branch_manager')) //branch manager
            ->first();

        $branch_manager->permissions()->attach(
            Permission::query()->whereIn('name', $this->branchManagerRolePermissions())
                ->pluck('id')->toArray()
        );

        $this->enableForeignKeys();
    }

    function branchManagerRolePermissions(): array
    {
        return [
            //dashboard
            'dashboard_tenant',

            'created_users',

            //lot
            'lot_manage',
            'view_lot',
            'create_lot',
            'update_lot',

            //branch-warehouse
            'view_branch_or_warehouses',

            //stock
            'view_stock',
            'import_stock',
            'view_stock_overview',

            //stockAdjustment
            'view_stock_adjustments',
            'create_stock_adjustments',
            'update_stock_adjustments',
            'delete_stock_adjustments',

            //barcode_qrcode
            'generate_barcode_inventory',
            'generate_qrcode',
            'manage_barcode_generate',
            'manage_qrcode_generate',

            //product
            'products_manage',
            'view_products',
            'create_products',
            'update_products',
            'delete_products',
            'import_products',
            'change_status_products',

            //group
            'view_groups',
            'create_groups',
            'update_groups',
            'delete_groups',

            //category
            'categories_manage',
            'view_categories',
            'create_categories',
            'update_categories',
            'delete_categories',
            'change_category_status',

            //subcategory
            'view_sub_categories ',
            'create_sub_categories',
            'update_sub_categories',
            'delete_sub_categories',
            'change_status_subcategory',

            //brand
            'view_brands',
            'create_brands',
            'update_brands',
            'delete_brands',
            'export_brands',

            //unit
            'view_unit',
            'create_unit',
            'update_unit',
            'delete_unit',
            'change_unit_status',
            'export_units',

            //attribute
            'view_attributes',
            'create_attributes',
            'update_attributes',
            'delete_attributes',
            'import_attributes',
            'export_attributes',

            //expense
            'view_expenses',
            'create_expenses',
            'update_expenses',
            'delete_expenses',

            //expense_areas
            'view_expense_areas',
            'create_expense_areas',
            'update_expense_areas',
            'delete_expense_areas',

            //customers
            'view_customers',
            'create_customers',
            'update_customers',
            'delete_customers',
            'details_customer',
            'info_customer',

            //suppliers
            'view_suppliers',
            'create_suppliers',
            'update_suppliers',
            'delete_suppliers',
            'change_status_supplier',

            //address
            'view_address',
            'create_address',
            'update_address',
            'delete_address',
            'areas_address',
            'areas_city',

            //customer_group
            'view_customer_groups',
            'create_customer_groups',
            'update_customer_groups',
            'delete_customer_groups',

            //sales_view
            'view_sales',

            //sales_return
            'create_return_order',
            'view_order_return',

            //sales_invoice
            'view_invoice',
            'list_invoice',
            'sales_invoice_generate',

            //reports
            'view_sales_report',
            'view_cash_counter_report',
            'view_sales_return_report',
            'view_top_selling_product_report',
            'view_lot_report',
            'view_stock_report',
            'view_branch_warehouse_report',
            'view_profit_loss_report',
            'view_user_report',
            'view_expense_report',
            'view_supplier_report',
            'view_customer_report',
            'export_sales',
            'export_cash_counter',
            'export_sales_return',
            'export_users',
        ];
    }
}
