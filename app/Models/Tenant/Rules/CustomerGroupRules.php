<?php

namespace App\Models\Tenant\Rules;

trait CustomerGroupRules
{
    public function createdRules(): array
    {
        return [
            'name' => 'required|string|max:120|unique:customer_groups,name',
        ];
    }

    public function updatedRules(): array
    {
        return [
            'name' => 'required|string|max:120|unique:customer_groups,name,'.request()->id,
        ];
    }
}