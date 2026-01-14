<?php

namespace App\Filters\Pos\Inventory;

use App\Filters\Core\traits\CreatedByFilter;
use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use App\Filters\Traits\SearchByFirstOrLastNameFilterTrait;
use Illuminate\Database\Eloquent\Builder;

class AdjustmentFilter extends FilterBuilder
{
    use DateRangeFilter,CreatedByFilter, SearchByFirstOrLastNameFilterTrait;

    public function search($search = null)
    {
        $this->builder->where('reference_no', 'LIKE', "%{$search}%");
    }

    public function branchOrWarehouse($branch_or_warehouse_id = null)
    {
        $branch_or_warehouse_id ? $this->builder->where('branch_or_warehouse_id', $branch_or_warehouse_id) : null;
    }

    //branch or warehouse
    public function type($type = null)
    {
        $this->builder->when($type, function (Builder $builder) use ($type) {
            $builder->whereHas('branchOrWarehouse', function (Builder $q) use ($type){
                $q->where('type', $type);
            });
        });
    }


}