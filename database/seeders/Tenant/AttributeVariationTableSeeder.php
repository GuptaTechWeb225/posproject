<?php


namespace Database\Seeders\Tenant;


use App\Models\Pos\Product\Attribute\AttributeVariation;
use Illuminate\Database\Seeder;

class AttributeVariationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'attribute_id' => 1,
                'name' => 'Red'
            ],
            [
                'attribute_id' => 1,
                'name' => 'white'
            ],
            [
                'attribute_id' => 1,
                'name' => 'Black'
            ],
            [
                'attribute_id' => 1,
                'name' => 'blue'
            ],

            [
                'attribute_id' => 2,
                'name' => 'S'
            ],
            [
                'attribute_id' => 2,
                'name' => 'm'
            ],
            [
                'attribute_id' => 2,
                'name' => 'L'
            ],
            [
                'attribute_id' => 2,
                'name' => 'XL'
            ],
        ];
        AttributeVariation::query()->insert($data);
    }

}