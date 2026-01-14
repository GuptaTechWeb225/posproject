<?php


namespace App\Models\Tenant\Rules;


trait ExpenseRules
{
    public function createdRules()
    {
        return [
            'name' => 'required',
            'branch_or_warehouse_id' => 'required',
            'expense_area_id' => 'required',
            'amount' => 'required|numeric|min:0',
            'attachments.*' => 'nullable'
        ];
    }

    public function updatedRules()
    {
        return [
            'name' => 'required',
            'branch_or_warehouse_id' => 'required',
            'expense_area_id' => 'required',
            'amount' => 'required|numeric|min:0',
        ];
    }
}