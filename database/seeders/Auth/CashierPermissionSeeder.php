<?php

namespace Database\Seeders\Auth;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class CashierPermissionSeeder extends Seeder
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

        $cashier = Role::query()
            ->where('name', config('access.users.app_cashier')) //Cashier
            ->first();

        $cashier->permissions()->attach(
            Permission::query()->whereIn('name', $this->cashierRolePermissions())
                ->pluck('id')->toArray()
        );

        $this->enableForeignKeys();
    }

    function cashierRolePermissions(): array
    {
        return [
            //dashboard
            'dashboard_tenant',

            //branch-warehouse
            'view_branch_or_warehouses',

            //customers
            'view_customers',
            'create_customers',
            'details_customer',

            //address
            'view_address',
            'create_address',
            'update_address',
            'delete_address',
            'areas_address',
            'areas_city',

            'view_sales',

            'create_return_order',
            'view_order_return',

            'view_invoice',
            'list_invoice',
            'sales_invoice_generate',

            //due collection permission
            'info_due',
            'receive_due',
        ];
    }
}
