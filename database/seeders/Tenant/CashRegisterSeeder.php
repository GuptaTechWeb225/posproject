<?php

namespace Database\Seeders\Tenant;

use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Tenant\Sales\Cash\CashRegister;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Seeder;

class CashRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branches = BranchOrWarehouse::query()->where('type', 'branch')->get();

        foreach ($branches as $branch) {
            CashRegister::query()->create(
                [
                    'name' => 'Counter one',
                    'branch_or_warehouse_id' => $branch->id,
                    'created_by' => 1,
                    'status_id' => resolve(StatusRepository::class)->cash_counterClose()
                ]
            );
        }
    }
}
