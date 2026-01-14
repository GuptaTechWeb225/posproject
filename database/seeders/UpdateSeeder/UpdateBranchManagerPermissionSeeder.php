<?php

namespace Database\Seeders\UpdateSeeder;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class UpdateBranchManagerPermissionSeeder extends Seeder
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
            //sales_invoice
            'sales_invoice_generate',
        ];
    }
}
