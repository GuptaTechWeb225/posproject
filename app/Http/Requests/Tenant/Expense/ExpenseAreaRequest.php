<?php


namespace App\Http\Requests\Tenant\Expense;


use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Tenant\Expense\ExpenseArea;

class ExpenseAreaRequest extends TenantRequest
{
    public function rules(): array
    {
        return $this->initRules( new ExpenseArea());
    }

}