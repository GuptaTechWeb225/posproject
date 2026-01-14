<?php

namespace Database\Seeders\Auth;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class WarehouseManagerPermissionSeeder extends Seeder
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
        $warehouse_manager = Role::query()
            ->where('name', config('access.users.app_warehouse_manager')) //warehouse manager
            ->first();

        $warehouse_manager->permissions()->attach(
            Permission::query()->whereIn('name', $this->warehouseManagerRolePermissions())
                ->pluck('id')->toArray()
        );

        $this->enableForeignKeys();
    }

    function warehouseManagerRolePermissions(): array
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

            // pos view and products
            'view_sales',
            'view_products',
            'products_manage',

            //reports
            'view_lot_report',
            'view_stock_report',
        ];
    }
}
