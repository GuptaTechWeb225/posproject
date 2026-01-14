<?php

namespace App\Filters\Traits;

trait SearchByFirstOrLastNameFilterTrait
{
    public function searchByFirstOrLastName($value = null)
    {
        $this->builder
            ->where('first_name', 'LIKE', "%{$value}%",)
            ->orWhere('last_name', 'LIKE', "%{$value}%");
    }

}
