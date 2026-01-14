<?php


namespace App\Services\Tenant\Coupon;


use App\Models\Tenant\Discount\Coupon;
use App\Services\Tenant\TenantService;

class CouponServices extends TenantService
{
    public function __construct(Coupon $coupon)
    {
        $this->model = $coupon;
    }
}