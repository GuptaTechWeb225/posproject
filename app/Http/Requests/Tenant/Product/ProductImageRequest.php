<?php


namespace App\Http\Requests\Tenant\Product;


use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Pos\Product\Product\Product;

class ProductImageRequest extends TenantRequest
{
    public function rules(): array
    {
        return $this->initRules(new Product());
    }
}