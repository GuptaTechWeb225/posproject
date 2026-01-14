<?php

namespace App\Http\Requests\Tenant\Contact;

use App\Http\Requests\Tenant\TenantRequest;

class CustomerImportRequest extends TenantRequest
{
    public function rules()
    {
        return [
            'customers' => 'required|mimes:csv,txt'
        ];
    }
}
