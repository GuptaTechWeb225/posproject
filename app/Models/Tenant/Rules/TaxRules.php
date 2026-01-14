<?php

namespace App\Models\Tenant\Rules;

trait TaxRules
{
    public function createdRules()
    {
        return [
            'name' => 'required',
            'type' => 'required',
            'percentage' => 'required_if:type,single_tax|numeric|max:100|min:0',
            'tax_id' => 'required_if:type,multi_tax',
            'is_default' => 'required',
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }

    public function massage()
    {
        return [
            'name.required' => 'Name is required',
            'percentage.required_if' => 'Percentage is required',
            'tax_id.required_if' => 'Group by is required',
        ];
    }
}