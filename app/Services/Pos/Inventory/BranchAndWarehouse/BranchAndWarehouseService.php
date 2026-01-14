<?php

namespace App\Services\Pos\Inventory\BranchAndWarehouse;

use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Tenant\Sales\Cash\CashRegister;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\Counter\CounterService;
use App\Services\Tenant\TenantService;

class BranchAndWarehouseService extends TenantService
{

    public function __construct(BranchOrWarehouse $branchOrWarehouse, CounterService $counterService)
    {
        $this->model = $branchOrWarehouse;
    }

    public function changeStatus(): bool
    {
        $this->getModel()->update([
            'status_id' => $this->getAttr('status') === true
                ? resolve(StatusRepository::class)->branchorwarehouseActive()
                : resolve(StatusRepository::class)->branchorwarehouseInactive()
        ]);

        return true;
    }

    public function storeBranchOrWarehouse($attributes): static
    {
        $this->model = $this->model::query()->create($attributes);
        return $this;
    }

    public function createDefaultCashRegister(): static
    {
        if ($this->model->type == 'branch'){
            CashRegister::query()->create([
                'name' => $this->model->name. "'s default counter",
                'branch_or_warehouse_id' => $this->model->id,
                'opening_time'=> now(),
                'status_id' =>  resolve(StatusRepository::class)->cash_counterClose(),
            ]);
        }

        return $this;
    }

    public function checkBranchWarehouseActive(): bool
    {
        return $this->model::checkBranchOrWarehouseIsActive(request()->branch_or_warehouse_id);
    }
}
