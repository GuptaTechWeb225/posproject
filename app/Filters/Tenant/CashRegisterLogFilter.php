<?php

namespace App\Filters\Tenant;

use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use Illuminate\Database\Eloquent\Builder;

class CashRegisterLogFilter extends FilterBuilder
{
    use DateRangeFilter;

    public function search($value = null)
    {
        return $this->builder->when($value, function (Builder $builder) {
            $builder->whereHas('cashRegister', function (Builder $builder) {
                $value = request()->get('search');
                $builder->where('name', 'LIKE', "%{$value}%");
            });
        });
    }
}