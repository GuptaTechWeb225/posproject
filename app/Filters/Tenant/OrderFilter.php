<?php

namespace App\Filters\Tenant;

use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends FilterBuilder
{
    public function search($search = null)
    {
        $this->builder->when($search, function (Builder $builder) use ($search) {
            $builder->where('invoice_number', 'LIKE', "%{$search}%");
        });
    }
    public function invoiceNumber($invoiceNumber = null)
    {
        $this->builder->when($invoiceNumber, function (Builder $builder) use ($invoiceNumber) {
            $builder->where('id', $invoiceNumber);
        });
    }
    public function createdBy($createdBy = null)
    {
        $this->builder->when($createdBy, function (Builder $builder) use ($createdBy) {
            $builder->where('created_by', $createdBy);
        });
    }

    public function date($date = null)
    {
        $date = json_decode(htmlspecialchars_decode($date), true);

        $this->builder->when($date, function (Builder $builder) use ($date) {
            $builder->whereBetween(\DB::raw('DATE(ordered_at)'), array_values($date));
        });
    }

    public function rangeFilter($range = null)
    {
        $chargeRange = json_decode(htmlspecialchars_decode($range), true);

        $this->builder->when($range, function (Builder $builder) use ($chargeRange) {
            $builder->where('sub_total', '>=', $chargeRange['min'])
                ->where('sub_total', '<=', $chargeRange['max']);
        });
    }


    public function customers($value = null)
    {
        $this->builder->when($value, function (Builder $builder) use ($value) {
            $builder->whereIn('customer_id', explode(',', $value));
        });
    }

    public function statusId($value = null)
    {
        $this->builder->when($value, function (Builder $builder) use ($value) {
            $builder->whereIn('status_id', explode(',', $value));
        });
    }

}