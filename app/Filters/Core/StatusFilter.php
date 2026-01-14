<?php


namespace App\Filters\Core;


use App\Filters\FilterBuilder;
use App\Filters\Traits\SearchByNameFilterTrait;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter extends FilterBuilder
{
    use SearchByNameFilterTrait;

    public function type($type = null)
    {
        $this->builder->when($type, function (Builder $builder) use ($type) {
            $builder->where('type', $type);
        });
    }
}
