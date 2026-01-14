<?php


namespace App\Services\Tenant\Stock;

use App\Models\Pos\Inventory\StockQuantity;
use App\Services\Tenant\TenantService;

class StockQuantityService extends TenantService
{
    public function __construct(StockQuantity $stockQuantity)
    {
        $this->model = $stockQuantity;
    }

}