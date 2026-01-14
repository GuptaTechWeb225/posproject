<?php


namespace App\Filters\Tenant;


use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class ExpenseAreaFilter extends FilterBuilder
{
    use SearchFilterTrait;

    public function branchOrWarehouse($branch = null)
    {
        $this->builder->when($branch, function (Builder $builder) use ($branch) {
            $builder->where('branch_or_warehouse_id', $branch);
        });
    }

}