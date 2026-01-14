<?php

namespace Database\Factories\Pos\Inventory;

use App\Models\Core\Auth\User;
use App\Models\Pos\Inventory\Adjustment\Adjustment;
use App\Models\Pos\Inventory\AdjustmentVariant;
use App\Models\Pos\Product\Product\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdjustmentVariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdjustmentVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $types = ['addition', 'subtraction'];
        return [
            'adjustment_id' => $this->faker->randomElement([...Adjustment::query()->pluck('id')]),
            'variant_id' => $this->faker->randomElement([...Variant::query()->pluck('id')]),
            'unit_quantity' => $this->faker->numberBetween(1,50),
            'adjustment_type' => $types[array_rand($types)],
            'created_by' => User::query()->first()->id,
        ];
    }
}
