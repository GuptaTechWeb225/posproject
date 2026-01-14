<?php

namespace Database\Factories\Pos\Inventory;

use App\Models\Pos\Inventory\InternalTransfer;
use App\Models\Pos\Inventory\InternalTransferVariant;
use App\Models\Pos\Product\Product\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

class InternalTransferVariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InternalTransferVariant::class;
    private static $lotNumber = 1;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'internal_transfer_id' => $this->faker->randomElement([...InternalTransfer::query()->pluck('id')]),
            'variant_id' => $this->faker->randomElement([...Variant::query()->pluck('id')]),
            'quantity' => $this->faker->numberBetween(1,50),
            'reference_no' => $this->faker->numberBetween(20000,500000),
        ];
    }
}
