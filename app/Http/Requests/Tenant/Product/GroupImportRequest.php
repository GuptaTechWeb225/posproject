<?php

namespace App\Http\Requests\Tenant\Product;

use App\Http\Requests\Tenant\TenantRequest;

class GroupImportRequest extends TenantRequest
{
    public function rules()
    {
        return [
            'productGroups' => 'required|mimes:csv,txt'
        ];
    }
}
