<?php


namespace App\Services\Tenant\Order;


use App\Models\Tenant\Order\OrderProduct;
use App\Services\Tenant\TenantService;

class OrderProductService extends TenantService
{
    public function __construct(OrderProduct $orderProduct)
    {
        $this->model = $orderProduct;
    }

}