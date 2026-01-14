<?php


namespace App\Models\Pos\Inventory\Rules;


trait LotVariantRules
{
    public function createdRules(): array
    {
        return [
            'variant_id' => 'required',
            'unit_quantity' => 'required',
            'unit_price' => 'required',
            'unit_tax_percentage' => 'required',
            'total_unit_price' => 'required'
        ];
    }

    public function updatedRules(): array
    {
        return $this->createdRules();
    }
}