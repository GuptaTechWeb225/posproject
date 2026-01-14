<?php

namespace Database\Seeders\Auth;

use App\Models\Core\Auth\User;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class UserRoleTableSeeder.
 */
class BranchOrWarehouseToUserTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
//        $this->disableForeignKeys();
//
//        $users = User::query()->where('id', '!=',1)->get(); //all users except app_admin
//        $branchWarehouses = BranchOrWarehouse::query()->get();
//
//        foreach ($users as $user) {
//            $user->branch_or_warehouse_id = rand(2, count($branchWarehouses));
//            $user->save();
//        }
//
//        $this->enableForeignKeys();



        $this->disableForeignKeys();

        //-------------------------------
        //Seed Users for branch
        //-------------------------------
        $branchUsers = User::query()
            ->where('id', '!=',1)
            ->where('email', '!=','warehousemanager@demo.com')
            ->get(); //all users except app_admin and warehousemanager

        $branches = BranchOrWarehouse::query()
            ->where('type', 'branch')
            ->first();

        foreach ($branchUsers as $user) {
            $user->branch_or_warehouse_id = $branches->id;
            $user->save();
        }

        //-------------------------------
        ////Seed User for Warehouse....
        //-------------------------------
        $warehouseUser = User::query()
            ->where('email','warehousemanager@demo.com')
            ->first(); //cashier

        $warehouse = BranchOrWarehouse::query()
            ->where('type', 'warehouse')
            ->first();

        $warehouseUser->branch_or_warehouse_id = $warehouse->id;
        $warehouseUser->save();

        $this->enableForeignKeys();
    }
}
