<?php

namespace App\Filters\Pos\Settings;

use App\Filters\Core\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class DiscountFilter extends BaseFilter
{
    public function search($search = null)
    {
        $this->builder->when($search, function (Builder $builder) use ($search) {
            $builder->where('name', 'LIKE', "%{$search}%");
        });
    }

    public function date($date = null)
    {
        $date = json_decode(htmlspecialchars_decode($date), true);

        $this->builder->when($date, function (Builder $builder) use ($date) {
            $builder->whereBetween(\DB::raw('DATE(ordered_at)'), array_values($date));
        });
    }

    public function statusId($value = null)
    {
        $this->builder->when($value, function (Builder $builder) use ($value) {
            $builder->whereIn('status_id', explode(',', $value));
        });
    }
}