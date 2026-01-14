<?php

namespace Database\Seeders\Auth;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();
        User::query()->truncate();

        // Add the master administrator, user id of 1
        $active_status_id = Status::findByNameAndType('status_active', 'user')->id;
        User::query()->insert([
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'admin@demo.com',
                'password' => Hash::make('123456'),
                'status_id' => $active_status_id
            ],
            [
                'first_name' => 'Joan',
                'last_name' => 'Toy',
                'email' => 'manager@demo.com',
                'password' => Hash::make('123456'),
                'status_id' => $active_status_id
            ],
            [
                'first_name' => 'Rosey',
                'last_name' => 'Martin',
                'email' => 'warehousemanager@demo.com',
                'password' => Hash::make('123456'),
                'status_id' => $active_status_id
            ],
            [
                'first_name' => 'Jack',
                'last_name' => 'Sparrow',
                'email' => 'branchmanager@demo.com',
                'password' => Hash::make('123456'),
                'status_id' => $active_status_id
            ],
            [
                'first_name' => 'Dr.',
                'last_name' => 'Banner',
                'email' => 'cashier@demo.com',
                'password' => Hash::make('123456'),
                'status_id' => $active_status_id
            ],
        ]);

        $this->enableForeignKeys();
    }
}
