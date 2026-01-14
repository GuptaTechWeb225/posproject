<?php

namespace App\Http\Controllers\Pos\Api\Product\Products;

use App\Exceptions\GeneralException;
use App\Filters\Tenant\BrandFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Product\BrandRequest;
use App\Models\Pos\Product\Brand\Brand;
use App\Services\Tenant\Product\BrandService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BrandController extends Controller
{
    public function __construct(BrandService $service, BrandFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index(): LengthAwarePaginator
    {
        return $this->service
            ->filters($this->filter)
            ->with('brandLogo')
            ->orderByDesc('id')
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    public function store(BrandRequest $request)
    {
        $this->service
            ->save(
                array_merge( ['created_by' => auth()->user()->id],
                    $request->only('name', 'description'))
            );

        $this->service->uploadBrandLogo($request->brand_logo);

        return created_responses('brand');
    }

    public function show(Brand $brand): Brand
    {
        return $brand->load('brandLogo');
    }

    public function update(BrandRequest $request, Brand $brand)
    {
        $brand->update(
            array_merge( ['created_by' => auth()->user()->id],$request->only('name', 'description'))
        );

        $this->service
            ->setModel($brand)
            ->uploadBrandLogo($request->brand_logo);

        return updated_responses('brand');
    }

    public function destroy(Brand $brand)
    {
        $productCount = $brand->products()->count();
        throw_if($productCount > 0, new GeneralException(__t('brand_in_use')));
        $brand->delete();

        return deleted_responses('brand');
    }
}
