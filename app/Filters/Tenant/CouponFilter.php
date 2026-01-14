<?php


namespace App\Filters\Tenant;


use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class CouponFilter extends FilterBuilder
{
    public function search($value = null): void
    {
        $this->builder
            ->where(function (Builder $builder) use ($value) {
                $builder->whereRaw("name LIKE ?",  "%{$value}%");
            })
            ->orWhere('code', 'LIKE', "%{$value}%")
            ->orWhere('discount', 'LIKE', "%{$value}%");
    }
}