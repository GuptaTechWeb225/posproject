<?php

namespace App\Filters\Traits;

trait SearchByNameFilterTrait
{
    public function searchByName($value = null)
    {
        $this->builder
            ->where('name', 'LIKE', "%{$value}%");
    }


}
