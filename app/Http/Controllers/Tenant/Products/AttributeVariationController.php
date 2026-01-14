<?php

namespace App\Http\Controllers\Tenant\Products;

use App\Http\Controllers\Controller;
use App\Models\Pos\Product\Attribute\AttributeVariation;
use App\Services\Tenant\Product\AttributeVariationService;
use Illuminate\Http\Request;

class AttributeVariationController extends Controller
{
    public function __construct(AttributeVariationService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
       $attribute =  $this->service
            ->setAttrs($request->only('attribute_id', 'name'))
            ->save();

        return [
            'id' => $attribute->id,
            'list' => AttributeVariation::all()
        ];
    }
}
