<?php

namespace Database\Factories\Pos\Inventory\LotVariant;

use App\Models\Pos\Inventory\Lot\Lot;
use App\Models\Pos\Inventory\LotVariant\LotVariant;
use App\Models\Pos\Product\Product\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

class LotVariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LotVariant::class;
    private static $lotNumber = 1;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'lot_id' => $this->faker->randomElement([...Lot::query()->pluck('id')]),
            'variant_id' => $this->faker->randomElement([...Variant::query()->pluck('id')]),
            'unit_quantity' => $this->faker->numberBetween(1,100),
            'unit_price' => $this->faker->numberBetween(20,200),
            'unit_tax_percentage' =>  $this->faker->numberBetween(1,20),
            'total_unit_price' => 0
        ];
    }
}
