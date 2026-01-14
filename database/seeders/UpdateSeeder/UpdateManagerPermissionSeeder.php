<?php

namespace Database\Seeders\UpdateSeeder;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class UpdateManagerPermissionSeeder extends Seeder
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

        $manager = Role::query()
            ->where('name', config('access.users.app_manager')) //manager
            ->first();

        $manager->permissions()->attach(
            Permission::query()->whereIn('name', $this->managerRolePermissions())
                ->pluck('id')->toArray()
        );

        $this->enableForeignKeys();
    }

    function managerRolePermissions(): array
    {
        return [
            //sales_invoice
            'sales_invoice_generate',
        ];
    }
}
