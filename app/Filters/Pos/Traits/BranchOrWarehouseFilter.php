<?php

namespace App\Filters\Pos\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BranchOrWarehouseFilter
{

    public function branchOrWarehouse($branch_or_warehouse_id = null)
    {
        $this->builder->when($branch_or_warehouse_id, function (Builder $builder) use ($branch_or_warehouse_id) {
            $builder->where('branch_or_warehouse_id', $branch_or_warehouse_id);
        });
    }
}