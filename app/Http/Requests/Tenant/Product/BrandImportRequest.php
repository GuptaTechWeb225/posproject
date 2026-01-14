<?php

namespace App\Http\Requests\Tenant\Product;

use App\Http\Requests\Tenant\TenantRequest;

class BrandImportRequest extends TenantRequest
{
    public function rules()
    {
        return [
            'brands' => 'required|mimes:csv,txt'
        ];
    }
}
