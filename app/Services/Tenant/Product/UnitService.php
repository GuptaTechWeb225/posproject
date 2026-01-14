<?php

namespace App\Services\Tenant\Product;

use App\Models\Pos\Product\Unit\Unit;
use App\Services\Tenant\TenantService;

class UnitService extends TenantService
{
    public function __construct(Unit $unit)
    {
        $this->model = $unit;
    }


}
