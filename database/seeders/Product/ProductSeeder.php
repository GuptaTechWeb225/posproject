<?php

namespace Database\Seeders\Product;

use Database\Seeders\Product\Traits\ProductSeederTrait;
use Database\Seeders\Product\Traits\ThumbnailSeederTrait;
use Database\Seeders\Product\Traits\VariantAttributeSeederTrait;
use Database\Seeders\Product\Traits\VariantSeederTrait;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    use ThumbnailSeederTrait, ProductSeederTrait, VariantSeederTrait, VariantAttributeSeederTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->productSeeder();

        $this->variantSeeder();

        $this->thumbnailSeeder();

        $this->variantAttributeSeeder();
    }
}
