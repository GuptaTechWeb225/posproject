<?php

namespace App\Filters\Tenant;

use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use Illuminate\Database\Eloquent\Builder;

class CustomerFilter extends FilterBuilder
{
    use DateRangeFilter;

    public function customer($value = null)
    {
        $this->builder->where('id',$value);
    }


    public function search($value = null)
    {
        $this->builder->where(function (Builder $builder) use ($value) {
            $builder->whereRaw("CONCAT(first_name,' ',last_name) LIKE ?", "%{$value}%")
                ->orWhere('tin', $value)
                ->orWhereHas('emails', function (Builder $builder) use ($value) {
                    $builder->where('value', $value);
                })
                ->orWhereHas('phoneNumbers', function (Builder $builder) use ($value) {
                    $builder->where('value', $value);
                });
        });
    }

    public function customerGroups($id = null)
    {
        $ids = explode(',', $id);

        $this->builder->when($id, function (Builder $builder) use ($ids) {
            $builder->whereIn('customer_group_id', $ids);
        });
    }

    public function addressArea($value = null)
    {
        $this->builder->when($value, function (Builder $builder) use ($value) {
            $builder->whereHas('addresses', function ($q) use ($value) {
                $q->where('area', 'LIKE', $value);
            });
        });
    }

    public function addressCity($value = null)
    {
        $this->builder->when($value, function (Builder $builder) use ($value) {
            $builder->whereHas('addresses', function ($q) use ($value) {
                $q->where('city', 'LIKE', $value);
            });
        });
    }
}