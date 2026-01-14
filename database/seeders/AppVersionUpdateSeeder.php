<?php

namespace Database\Seeders;

use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Database\Seeders\UpdateSeeder\RolePermissionUpdateSeeder;
use Database\Seeders\UpdateSeeder\UpdateBranchManagerPermissionSeeder;
use Database\Seeders\UpdateSeeder\UpdateCashierPermissionSeeder;
use Database\Seeders\UpdateSeeder\UpdateManagerPermissionSeeder;
use Illuminate\Database\Seeder;

class AppVersionUpdateSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(RolePermissionUpdateSeeder::class);
//        $this->call(UpdateBranchManagerPermissionSeeder::class);
//        $this->call(UpdateCashierPermissionSeeder::class);
//        $this->call(UpdateManagerPermissionSeeder::class);

    }
}
