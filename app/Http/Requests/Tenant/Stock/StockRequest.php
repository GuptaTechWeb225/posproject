<?php


namespace App\Http\Requests\Tenant\Stock;


use App\Http\Requests\Tenant\TenantRequest;
use Illuminate\Validation\Rule;

class StockRequest extends TenantRequest
{
    public function rules(): array
    {
        return [
            'lot_id' => 'required|exists:lots,id',
            'variant_id' => 'nullable|exists:variants,id',
            'tax_id' => 'required|exists:taxes,id',
            'created_by' => 'required|exists:users,id',
            'purchase_price' => 'required',
            'expire_date' => 'nullable|date|date_format:Y-m-d',
            'manufacturing_date' => 'required|date|date_format:Y-m-d',
            'bar_code' => 'required|unique:stocks',
            'sku' => 'required|unique:stocks',
            'tax_type' => ['required', Rule::in(['inclusive', 'exclusive'])],
        ];
    }
}