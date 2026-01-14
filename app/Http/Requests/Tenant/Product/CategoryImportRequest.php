<?php

namespace App\Http\Requests\Tenant\Product;

use App\Http\Requests\Tenant\TenantRequest;

class CategoryImportRequest extends TenantRequest
{
    public function rules()
    {
        return [
            'categories' => 'required|mimes:csv,txt'
        ];
    }
}
