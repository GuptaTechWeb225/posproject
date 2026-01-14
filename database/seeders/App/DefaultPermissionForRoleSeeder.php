<?php

namespace Database\Seeders\App;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultPermissionForRoleSeeder extends Seeder
{
    use DisableForeignKeys;

    public function run()
    {
        $this->disableForeignKeys();

            $roles = Role::query()->where('is_admin', 0)->get();
            $dashboard_permission = Permission::query()
                ->where('name', 'dashboard_tenant')
                ->first();

            foreach ($roles as $role) {
                $role->permissions()->attach([
                    'role_id' => $role->id,
                    'permission_id' => 1,
                ]);
            }

        $this->enableForeignKeys();
    }
}
