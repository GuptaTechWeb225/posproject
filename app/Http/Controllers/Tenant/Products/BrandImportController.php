<?php

namespace App\Http\Controllers\Tenant\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Product\BrandImportRequest;
use App\Services\Tenant\Product\BrandImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandImportController extends Controller
{
    public function __construct(BrandImportService $service)
    {
        $this->service = $service;
    }

    public function preview(BrandImportRequest $request)
    {

        return DB::transaction(function () use ($request) {
            $brands = $this->service
                ->setAttr('file', $request->file('brands'))
                ->preview();

            if (filled($brands['sanitized'])) {

                $this->service
                    ->setAttr('brands', $brands['sanitized'])
                    ->saveSanitized();
            }

            unset($brands['sanitized']);

            if (count($brands['filtered'])) {
                return $brands;
            }

            return created_responses('brand');

        });
    }


    public function store(Request $request): array
    {


        $response = $this->service
            ->setAttr('brands', $request->get('brands'))
            ->store();

        if (is_array($response)) {

            return $response;
        }

        return created_responses('brand');
    }

}
