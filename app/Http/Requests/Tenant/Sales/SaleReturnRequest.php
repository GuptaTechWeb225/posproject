<?php

namespace App\Http\Requests\Tenant\Sales;

use Illuminate\Foundation\Http\FormRequest;

class SaleReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'branch_or_warehouse_id' => 'required|exists:branch_or_warehouses,id',
            'created_by' => 'required|exists:users,id',
            'returned_at' => 'required|date|date_format:Y-m-d',
            'tenant_id' => 'required'
        ];
    }
}
