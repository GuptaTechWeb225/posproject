<?php

namespace App\Http\Controllers\Pos\Api\Product\Products;

use App\Exceptions\GeneralException;
use App\Filters\Tenant\AttributeFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Product\AttributeRequest;
use App\Models\Pos\Product\Attribute\Attribute;
use App\Services\Tenant\Product\AttributeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AttributeController extends Controller
{
    public function __construct(AttributeService $service, AttributeFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index(): LengthAwarePaginator
    {
        return $this->service
            ->filters($this->filter)
            ->with(['attributeVariations:id,attribute_id,name', 'createdBy:id,first_name,last_name'])
            ->orderByDesc('id')
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    public function store(AttributeRequest $request)
    {
        DB::transaction(function () use ($request) {
            $this->service
                ->save(
                    $request->only('name', 'created_by', 'tenant_id')
                );
            if ($request->get('attribute_variation')) {
                $this->service
                    ->setAttrs($request->only('attribute_variation'))
                    ->storeVariation();
            }
        });

        return created_responses('variant_attribute');
    }

    public function show(Attribute $attribute): Attribute
    {
        return $attribute->load('attributeVariations');
    }

    public function update(AttributeRequest $request, Attribute $attribute)
    {
        try {
            DB::transaction(function () use ($attribute, $request) {

                $attribute->update(
                    $request->only('name', 'created_by', 'tenant_id')
                );

                if ($request->get('attribute_variation')) {
                    $this->service
                        ->setModel($attribute)
                        ->setAttrs($request->only('attribute_variation'))
                        ->updateVariation();
                }
            });

            return updated_responses('variant_attribute');

        } catch (\Exception $e) {
            return throw_if($e, new GeneralException(__t('variation_is_in_use')));
        }
    }

    public function destroy(Attribute $attribute)
    {
        try {
            $attribute->attributeVariations()->delete();
            $attribute->delete();
        } catch (\Exception $e) {
            return throw_if($e, new GeneralException(__t('variation_is_in_use')));
        }

        return deleted_responses('variant_attribute');
    }
}
