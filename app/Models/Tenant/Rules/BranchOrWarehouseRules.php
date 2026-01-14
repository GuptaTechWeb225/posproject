<?php

namespace App\Models\Tenant\Rules;


trait BranchOrWarehouseRules
{
    public function createdRules(): array
    {
        return [
            'name' => 'required|unique:branch_or_warehouses,name',
            'type' => 'required',
        ];
    }

    public function updatedRules()
    {
        return [
            'name' => 'required|unique:branch_or_warehouses,name,' . request()->branch_or_warehouse->id,
            'type' => 'required',
        ];
    }
}