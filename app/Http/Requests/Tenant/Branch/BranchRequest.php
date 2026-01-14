<?php

namespace App\Http\Requests\Tenant\Branch;

use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Tenant\Branch\Branch;

class BranchRequest extends TenantRequest
{
    public function rules(): array
    {
        return $this->initRules(new Branch());
    }
}
