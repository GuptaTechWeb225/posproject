<?php

namespace App\Models\Tenant\Rules;


trait CustomerAddressRules
{
    public function createdRules()
    {
        return [
            'name' => 'required',
            'details' => 'required',
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}