<?php

namespace Database\Seeders\Product\Traits;

use App\Models\Pos\Product\AttributeVariationVariant\AttributeVariationVariant;

trait VariantAttributeSeederTrait
{
    public function variantAttributeSeeder()
    {
        //==========================================
        //04. attribute_variation_variant for variant products
        //==========================================
        $attrs = [
            //variant product 01
            [
                'attribute_variation_id' => 7,
                'variant_id' => 11,
            ],
            [
                'attribute_variation_id' => 7,
                'variant_id' => 12,
            ],
            [
                'attribute_variation_id' => 6,
                'variant_id' => 11,
            ],
            [
                'attribute_variation_id' => 6,
                'variant_id' => 12,
            ],
            [
                'attribute_variation_id' => 4,
                'variant_id' => 12,
            ],
            [
                'attribute_variation_id' => 4,
                'variant_id' => 11,
            ],
            [
                'attribute_variation_id' => 3,
                'variant_id' => 11,
            ],
            [
                'attribute_variation_id' => 1,
                'variant_id' => 11,
            ],
            [
                'attribute_variation_id' => 1,
                'variant_id' => 12,
            ],

            //variant product 02
            [
                'attribute_variation_id' => 6,
                'variant_id' => 13,
            ],
            [
                'attribute_variation_id' => 1,
                'variant_id' => 13,
            ],
            [
                'attribute_variation_id' => 2,
                'variant_id' => 14,
            ],
            [
                'attribute_variation_id' => 6,
                'variant_id' => 14,
            ],
            [
                'attribute_variation_id' => 1,
                'variant_id' => 15,
            ],
            [
                'attribute_variation_id' => 7,
                'variant_id' => 15,
            ],
        ];

        AttributeVariationVariant::query()->insert($attrs);
    }

}