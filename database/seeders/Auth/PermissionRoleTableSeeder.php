<?php
namespace Database\Seeders\Auth;

use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Models\Core\Auth\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();
        Role::query()->truncate();
        // Create Roles
        $superAdmin = User::first();

        //****************************************************************************************************************
        //Default permission for role is seeded according to this role table seeding hierarchy, so it shouldn't change.
        //****************************************************************************************************************
        $roles = [
            [
                'name' => config('access.users.app_admin_role'),
                'is_admin' => 1,
                'type_id' => Type::findByAlias('app')->id,
                'created_by' => $superAdmin->id,
                'is_default' => 1,
                'is_manager' => 0,
                'is_warehouse_manager' => 0,
            ],
            [
                'name' => config('access.users.app_manager'),
                'is_admin' => 0,
                'type_id' => Type::findByAlias('app')->id,
                'created_by' => $superAdmin->id,
                'is_default' => 0,
                'is_manager' => 1,
                'is_warehouse_manager' => 0,
            ],
            [
                'name' => config('access.users.app_warehouse_manager'),
                'is_admin' => 0,
                'type_id' => Type::findByAlias('app')->id,
                'created_by' => $superAdmin->id,
                'is_default' => 0,
                'is_manager' => 0,
                'is_warehouse_manager' => 1,
            ],
            [
                'name' => config('access.users.app_branch_manager'),
                'is_admin' => 0,
                'type_id' => Type::findByAlias('app')->id,
                'created_by' => $superAdmin->id,
                'is_default' => 0,
                'is_manager' => 0,
                'is_warehouse_manager' => 0,
            ],
            [
                'name' => config('access.users.app_cashier'),
                'is_admin' => 0,
                'type_id' => Type::findByAlias('app')->id,
                'created_by' => $superAdmin->id,
                'is_default' => 0,
                'is_manager' => 0,
                'is_warehouse_manager' => 0,
            ]
        ];

        Role::query()->insert($roles);

        $this->enableForeignKeys();
    }
}
