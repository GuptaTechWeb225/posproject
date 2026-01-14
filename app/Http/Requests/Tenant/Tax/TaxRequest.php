<?php

namespace App\Http\Requests\Tenant\Tax;

use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Pos\Product\Tax\Tax;

class TaxRequest extends TenantRequest
{

    public function rules(): array
    {
        return $this->initRules(new Tax());
    }

    public function messages(): array
    {
        return resolve(Tax::class)->massage();
    }
}
