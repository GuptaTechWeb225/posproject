<?php

namespace App\Models\Pos\Inventory\Rules;


trait CashRegisterRules
{

    public function createdRules()
    {
        return [
            'name' => 'required|unique:cash_registers,name',
            'branch_or_warehouse_id' => 'required'
        ];
    }

    public function updatedRules()
    {
        return [
            'name' => 'required|unique:cash_registers,name,' . request()->counter->id,
            'branch_or_warehouse_id' => 'required'
        ];
    }
}