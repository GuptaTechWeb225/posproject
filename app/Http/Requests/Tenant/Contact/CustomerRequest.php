<?php

namespace App\Http\Requests\Tenant\Contact;

use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Tenant\Customer\Customer;

class CustomerRequest extends TenantRequest
{
    public function rules(): array
    {
        return $this->initRules( new Customer());
    }

    public function messages(): array
    {
        return resolve(Customer::class)->message();
    }

}