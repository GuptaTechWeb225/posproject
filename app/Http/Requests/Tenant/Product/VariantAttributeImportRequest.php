<?php

namespace App\Http\Requests\Tenant\Product;

use App\Http\Requests\Tenant\TenantRequest;

class VariantAttributeImportRequest extends TenantRequest
{
    public function rules()
    {
        return [
            'variantAttributes' => 'required|mimes:csv,txt'
        ];
    }
}
