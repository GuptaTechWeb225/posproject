<?php


namespace App\Models\Pos\Inventory\Rules;


trait InternalTransferRules
{
    public function createdRules(): array
    {
        return [
            'branch_or_warehouse_from_id' => 'required',
            'branch_or_warehouse_to_id' => 'required|different:branch_or_warehouse_from_id',
            'date' => 'required|date|date_format:Y-m-d',
            'status_id' => 'required',
            'reference_no' => 'required',

            //internal transfer variant
            'internalTransferVariants.*.variant_id'=> 'required',
            'internalTransferVariants.*.unit_quantity'=> 'required',
            'internalTransferVariants.*.lot_reference_no'=> 'required',

        ];
    }

    public function updatedRules(): array
    {
        return [
            'branch_or_warehouse_from_id' => 'required',
            'branch_or_warehouse_to_id' => 'required|different:branch_or_warehouse_from_id',
            'date' => 'required|date|date_format:Y-m-d',
            'status_id' => 'required',
            'reference_no' => 'required|unique:internal_transfers,reference_no,'.request()->id,

            //internal transfer variant
            'internalTransferVariants.*.variant_id'=> 'required',
            'internalTransferVariants.*.unit_quantity'=> 'required',
            'internalTransferVariants.*.lot_reference_no'=> 'required',
        ];
    }
}