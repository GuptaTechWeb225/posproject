<?php

namespace App\Http\Requests\Tenant\Counter;

use App\Http\Requests\BaseRequest;
use App\Models\Tenant\Sales\Cash\CashRegister;

class CounterRequest extends BaseRequest
{
    public function rules(): array
    {
        return $this->initRules( new CashRegister());
    }
}