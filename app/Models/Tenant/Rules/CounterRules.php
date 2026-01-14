<?php

namespace App\Models\Tenant\Rules;

trait CounterRules
{
    public function createdRules()
    {
        return [
            'name' => 'required',
            'tenant_id' => 'required',
            'branch_id' => 'required',
            'sales_person' => 'exists:users,id'
        ];
    }

    public function updateRules()
    {
        return $this->createdRules();
    }
}