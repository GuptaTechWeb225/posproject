<?php

namespace App\Filters\Traits;

trait FirstNameFilter
{
    public function name ($firstName = null)
    {
        $this->whereClause('first_name', "%{$firstName}%", "LIKE");
    }
}
