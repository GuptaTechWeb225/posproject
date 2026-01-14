<?php

namespace App\Http\Requests\Pos\Product\Duration;

use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Pos\Product\Duration\Duration;

class DurationRequest extends TenantRequest
{
    public function rules(): array
    {
        return $this->initRules(new Duration());
    }
}
