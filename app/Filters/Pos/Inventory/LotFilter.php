<?php


namespace App\Filters\Pos\Inventory;

use App\Filters\Core\traits\StatusIdFilter;
use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use Illuminate\Database\Eloquent\Builder;

class LotFilter extends FilterBuilder
{
    use DateRangeFilter, StatusIdFilter;

    public function search($value = null)
    {
        $this->builder->where('reference_no', 'LIKE', "%{$value}%");
    }

    public function supplier($supplier_id = null)
    {
        $this->builder->when($supplier_id, function (Builder $builder) use ($supplier_id) {
            $builder->where('supplier_id', $supplier_id);
        });
    }

    public function branchOrWarehouse($branch_or_warehouse_id = null)
    {
        $this->builder->when($branch_or_warehouse_id, function (Builder $builder) use ($branch_or_warehouse_id) {
            $builder->where('branch_or_warehouse_id', $branch_or_warehouse_id);
        });
    }


}