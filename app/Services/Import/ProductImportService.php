<?php

namespace App\Services\Import;

use App\Models\Pos\Product\Attribute\AttributeVariation;
use App\Models\Pos\Product\AttributeVariationVariant\AttributeVariationVariant;
use App\Models\Pos\Product\Product\Variant;
use App\Services\Core\BaseService;
use App\Services\Tenant\Product\ProductService;

class ProductImportService extends BaseService
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }


    public function storeProduct(): ProductImportService
    {
        $exist_product = $this->productService->query()
            ->where('name', $this->getAttr('name'))
            ->first();

        if ($exist_product){
            $this->setAttr('product_id',$exist_product->id);
            $this->setAttr('exist_product',true);
        }else{
            $product = $this->productService
                ->save($this->getAttributes());
            $this->setAttr('exist_product',false);
            $this->setAttr('product_id',$product->id);
        }
        return $this;
    }


    public function storeVariantData(): ProductImportService
    {
        //if single product is already exist then skip
        if ($this->getAttr('product_type') === 'single'){
            if (!$this->getAttr('exist_product')){
                $this->storeEachVariantData();
            }
        }

        if ($this->getAttr('product_type') === 'variant'){
            $variantNameExist = Variant::query()
                ->where('name',$this->getAttr('variant_name'))
                ->first();

            //create new variant when note exist with same name
            if (!$variantNameExist){
                $variant_value = $this->getAttr('variant_value');
                $variations = explode(',', $variant_value);

                $variant = $this->storeEachVariantData();

                $newData = [];
                for ($i=0; $i < count($variations); $i++ ){
                    $attr = AttributeVariation::query()->where('name',$variations[$i])->first();

                    if ($attr){
                        $newData[$i]['attribute_variation_id'] = $attr->id;
                        $newData[$i]['variant_id'] = $variant->id;
                    }
                }

                AttributeVariationVariant::query()->insert($newData);
            }
        }

        return $this;
    }


    private function storeEachVariantData()
    {
        $variant = Variant::query()->create([
            'product_id'    => $this->getAttr('product_id'),
            'selling_price' => $this->getAttr('selling_price'),
            'upc'           => $this->getAttr('upc'),
            'status_id'     => $this->getAttr('status_id'),
            'stock_reminder_quantity' => $this->getAttr('stock_reminder_quantity'),
            'name' => $this->getAttr('variant_name') ?? null,
        ]);

        return $variant;
    }
}