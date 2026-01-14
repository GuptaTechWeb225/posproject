<?php


namespace App\Http\Requests\Tenant\Expense;


use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Tenant\Expense\Expense;

class ExpenseRequest extends TenantRequest
{
    public function rules(): array
    {
        return $this->initRules( new Expense());
    }

    public function messages(): array
    {
        return [
            'supplier_id' => 'Select a supplier',
            'branch_or_warehouse_id' => 'Select branch or warehouse',
            'expense_area_id' => 'Select expense area',
        ];
    }
}