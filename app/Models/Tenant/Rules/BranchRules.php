<?php

namespace App\Models\Tenant\Rules;


trait BranchRules
{
    public function createdRules()
    {
        return [
            'name' => 'required',
            'manager_id' => 'required',
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}