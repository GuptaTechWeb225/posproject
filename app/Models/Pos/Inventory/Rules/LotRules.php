<?php


namespace App\Models\Pos\Inventory\Rules;

trait LotRules
{
    public function createdRules(): array
    {
        return [
            'supplier_id' => 'required',
            'purchase_date' => 'required|date|date_format:Y-m-d',
            'status_id' => 'required',
            'reference_no' => 'required|unique:lots,reference_no',
            'total_amount' => 'required',
            'branch_or_warehouse_id' => 'required',
        ];
    }

    public function updatedRules(): array
    {
        return [
            'supplier_id' => 'required',
            'purchase_date' => 'required|date|date_format:Y-m-d',
            'status_id' => 'required',
            'branch_or_warehouse_id' => 'required',
            'reference_no' => 'required|unique:lots,reference_no,'.request()->lot->id,
        ];
    }
}