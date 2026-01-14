<?php

namespace Database\Seeders\UpdateSeeder;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class UpdateCashierPermissionSeeder extends Seeder
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
            'sales_invoice_generate',
        ];
    }
}
