<?php

namespace App\Http\Requests\Tenant\Product;

use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Pos\Product\Product\Variant;

class VariantRequest extends TenantRequest
{

    /**
     * @throws \App\Exceptions\GeneralException
     */
    public function rules(): array
    {
        return $this->initRules(new Variant());
    }
}
