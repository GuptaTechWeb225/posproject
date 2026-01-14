<?php

namespace App\Http\Requests\Tenant\Sales;

use Illuminate\Foundation\Http\FormRequest;

class CashRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
            'branch_or_warehouse_id' => 'required',
            'action' => 'required|in:open,close',
            'opening_time' => 'required_if:action,open|date:Y-m-d H:i:s',
            'closing_time' => 'required_if:action,close|date:Y-m-d H:i:s',
        ];
    }
}
