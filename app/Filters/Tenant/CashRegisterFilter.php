<?php

namespace App\Filters\Tenant;

use App\Filters\Core\traits\NameFilter;
use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;

class CashRegisterFilter extends FilterBuilder
{
    use NameFilter, SearchFilterTrait, DateRangeFilter;

    public function branchOrWarehouse($branch_or_warehouse_id = null)
    {
        $branch_or_warehouse_id
            ? $this->builder->where('branch_or_warehouse_id', $branch_or_warehouse_id)
            : null;
    }


}