<?php

namespace Database\Seeders\Auth;

use App\Models\Core\Auth\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class UserRoleTableSeeder.
 */
class UserRoleTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        //seed app admin
        $user = User::query()->find(1);

        $app_roles = [
            config('access.users.app_manager'),
            config('access.users.app_warehouse_manager'),
            config('access.users.app_branch_manager'),
            config('access.users.app_cashier'),
        ];

        $users = User::query()->get();
        foreach ($users as $user) {
            if ($user->id === 1){
                $user->assignRole(config('access.users.app_admin_role'));

            }elseif ($user->id > 1 AND $user->id < 6 ) {
                //seed other 4 user with similar email and roles (except app admin)
                $user->assignRole($user->id);

            } else{
                //seed other user with random 4 roles
                $user->assignRole($app_roles[rand(0, 3)]);
            }
        }

        $this->enableForeignKeys();
    }
}
