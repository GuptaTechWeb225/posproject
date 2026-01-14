<?php

namespace App\Http\Requests\Tenant\PaymentMethod;

use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Tenant\PaymentMethod\PaymentMethod;

class PaymentMethodRequest extends TenantRequest
{

    public function rules(): array
    {
        return $this->initRules(new PaymentMethod());
    }
}
