<?php


namespace App\Services\Tenant\Product;

use App\Models\Pos\Product\Attribute\Attribute;
use App\Services\Tenant\TenantService;

class AttributeService extends TenantService
{
    public function __construct(Attribute $attribute)
    {
        $this->model = $attribute;
    }

    public function update()
    {
        $this->model->fill($this->getAttributes());
        $this->model->save();
        return $this;
    }

    private function variationInfo($variation, $attribute_id): array
    {
        return [
            'attribute_id' => $attribute_id,
            'name' => $variation,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    public function store(): Attribute
    {
        $this->model = $this->save(
            $this->getAttrs('name')
        );
        return $this->model;
    }

    public function storeVariation()
    {
        $variations = $this->getAttr('attribute_variation');

        $variationInfo = [];

        foreach ($variations as $key => $variation) {
            $variationInfo[] = $this->variationInfo($variation, $this->store()->id);
        }
        $this->store()
            ->attributeVariations()
            ->insert($variationInfo);
    }

    public function updateVariation()
    {

        $this->getModel()->attributeVariations()->delete();

        $variations = $this->getAttr('attribute_variation');

        $variationInfo = [];

        foreach ($variations as $key => $variation) {
            $variationInfo[] = $this->variationInfo($variation, $this->getModel()->id);
        }

        $this->store()
            ->attributeVariations()
            ->insert($variationInfo);
    }

}