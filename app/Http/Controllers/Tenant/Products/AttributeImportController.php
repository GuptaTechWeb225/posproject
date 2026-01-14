<?php

namespace App\Http\Controllers\Tenant\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Product\VariantAttributeImportRequest;
use App\Services\Tenant\Product\VariantAttributeImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeImportController extends Controller
{
    public function __construct(VariantAttributeImportService $service)
    {
        $this->service = $service;
    }

    public function preview(VariantAttributeImportRequest $request)
    {

        return DB::transaction(function () use ($request) {
            $variantAttributes = $this->service
                ->setAttr('file', $request->file('variantAttributes'))
                ->preview();

            if (filled($variantAttributes['sanitized'])) {

                $this->service
                    ->setAttr('variantAttributes', $variantAttributes['sanitized'])
                    ->saveSanitized();
            }

            unset($variantAttributes['sanitized']);

            if (count($variantAttributes['filtered'])) {
                return $variantAttributes;
            }

            return created_responses('attribute');

        });
    }


    public function store(Request $request): array
    {


        $response = $this->service
            ->setAttr('variantAttributes', $request->get('variantAttributes'))
            ->store();

        if (is_array($response)) {

            return $response;
        }

        return created_responses('attribute');
    }

}
