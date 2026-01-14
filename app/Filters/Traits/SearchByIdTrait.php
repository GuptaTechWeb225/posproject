<?php

namespace App\Filters\Traits;

trait SearchByIdTrait
{
    public function customerGroups ($id = null)
    {
        $this->whereClause('customer_group_id', $id);
    }
}
