<?php
    namespace App\Models\Pos\Inventory\Rules;

    trait AdjustmentRules
    {

        public function createdRules()
        {
            return [
                'branch_or_warehouse_id' => 'required',
                'reference_no' => 'required|unique:adjustments,reference_no',
                'adjustment_date' => 'required|date|date_format:Y-m-d',

                //Adjustment variant data
                'adjustmentVariants.*.variant_id'=> 'required',
                'adjustmentVariants.*.unit_quantity'=> 'required',
                'adjustmentVariants.*.adjustment_type'=> 'required',
            ];
        }

        public function updatedRules()
        {
            return [
                'branch_or_warehouse_id' => 'required',
                'adjustment_date' => 'required|date|date_format:Y-m-d',

                //Adjustment variant data
                'adjustmentVariants.*.variant_id'=> 'required',
                'adjustmentVariants.*.unit_quantity'=> 'required',
                'adjustmentVariants.*.adjustment_type'=> 'required',
                'reference_no' => 'required|max:140|unique:adjustments,reference_no,'.request()->stock_adjustment->id
            ];
        }
    }


