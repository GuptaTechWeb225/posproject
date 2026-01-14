<?php

namespace Database\Seeders\BranchAndWarehouse;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use Illuminate\Database\Seeder;

class BranchAndWarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BranchOrWarehouse::query()->insert([
            [
                'name' => 'Los Angeles Branch',
                'phone' => '1-248-908-7946',
                'email' => 'branchmanager@demo.com',
//                'manager_id' => User::query()->first()->id,
                'manager_id' => User::query()->where('email', 'branchmanager@demo.com')->first()->id,
                'status_id' => Status::query()->where('name','status_active')->where('type','branchOrWarehouse')->first()->id,
                'location' => '5772 Audra Camp Apt. 468 Madieberg, MT 29470-7571',
                'tenant_id' => 1,
                'tax_id' => 3,
                'type'=> 'branch',
            ],
            [
                'name' => 'Houston Warehouse',
                'phone' => '1-682-451-2268',
                'email' => 'warehousemanager@demo.com',
                'manager_id' => User::query()->where('email', 'warehousemanager@demo.com')->first()->id,
                'status_id' => Status::query()->where('name','status_active')->where('type','branchOrWarehouse')->first()->id,
                'location' => '7830 Gibson Fields New Sedrickmouth, MT 70370-4305',
                'tenant_id' => 1,
                'tax_id' => 3,
                'type'=> 'warehouse',
            ]
        ]);
    }
}
