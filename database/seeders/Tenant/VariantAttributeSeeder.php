<?php

namespace Database\Seeders\Tenant;

use App\Models\Pos\Product\Attribute\AttributeVariation;
use App\Models\Pos\Product\Product\Variant;
use Illuminate\Database\Seeder;

class VariantAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AttributeVariation::query()->take(2)->get()->each(function ($attribute_variation) {
            $attribute_variation->variants()->sync(Variant::query()->pluck('id'));
        });

    }
}
