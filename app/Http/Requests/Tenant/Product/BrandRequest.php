<?php


namespace App\Http\Requests\Tenant\Product;


use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Pos\Product\Brand\Brand;

class BrandRequest extends TenantRequest
{

    public function rules(): array
    {
        return $this->initRules(new Brand());
    }

}