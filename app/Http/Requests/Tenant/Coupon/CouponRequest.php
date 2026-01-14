<?php

namespace App\Http\Requests\Tenant\Coupon;

use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Tenant\Discount\Coupon;

class CouponRequest extends TenantRequest
{
    /**
     * @throws \App\Exceptions\GeneralException
     */
    public function rules(): array
    {
        return $this->initRules(new Coupon());
    }
}
