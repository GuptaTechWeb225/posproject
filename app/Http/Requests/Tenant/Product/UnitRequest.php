<?php

namespace App\Http\Requests\Tenant\Product;

use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Pos\Product\Unit\Unit;

class UnitRequest extends TenantRequest
{
    public function rules(): array
    {
        return $this->initRules(new Unit());
    }
}
