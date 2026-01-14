<?php


namespace App\Filters\Tenant;


use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class ExpenseFilter extends FilterBuilder
{
    use SearchFilterTrait;

    public function expenseArea($ids = null)
    {
        $expense_area_ids = [];

        if ($ids) {
            $expense_area_ids = explode(',', $ids);
        }

        $this->builder->when(count($expense_area_ids),
            function (Builder $builder) use ($expense_area_ids) {
                $builder->whereIn('expense_area_id', $expense_area_ids);
            }
        );
    }

    public function expenseDate($date = null)
    {
        $date = json_decode(htmlspecialchars_decode($date), true);

        $this->builder->when($date, function (Builder $builder) use ($date) {
            $builder->whereBetween(\DB::raw('DATE(expense_date)'), array_values($date));
        });
    }

    public function branchOrWarehouse($branch = null)
    {
        $this->builder->when($branch, function (Builder $builder) use ($branch) {
            $builder->where('branch_or_warehouse_id', $branch);
        });
    }

}