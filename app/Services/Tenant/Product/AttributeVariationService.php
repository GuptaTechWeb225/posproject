<?php


namespace App\Services\Tenant\Product;


use App\Models\Pos\Product\Attribute\AttributeVariation;
use App\Services\Tenant\TenantService;

class AttributeVariationService extends TenantService
{
    public function __construct(AttributeVariation $attributeVariation)
    {
        $this->model = $attributeVariation;
    }
}