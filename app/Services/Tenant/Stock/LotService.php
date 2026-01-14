<?php


namespace App\Services\Tenant\Stock;


use App\Services\Tenant\TenantService;

class LotService extends TenantService
{
    public function __construct(Lot $lot)
    {
        $this->model = $lot;
    }
}