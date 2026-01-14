<?php

namespace App\Http\Requests\Tenant\Discount;

use Illuminate\Foundation\Http\FormRequest;

class DiscountRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'discount_type' => 'required',
            'branch_or_warehouse_id' => 'required|exists:branch_or_warehouses,id',
            'name' => 'required',
            'type' => 'required',
            'value' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'value' => 'amount'
        ];
    }


}
