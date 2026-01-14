<?php

namespace Database\Factories\Pos\Inventory\Adjustment;

use App\Models\Core\Auth\User;
use App\Models\Pos\Inventory\Adjustment\Adjustment;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdjustmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Adjustment::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {

        return [
            'branch_or_warehouse_id' => $this->faker->randomElement([...BranchOrWarehouse::query()->pluck('id')]),
            'adjustment_date' => $this->faker->dateTime,
            'reference_no' => $this->faker->unique()->numberBetween(100000, 500000),
            'note' => $this->faker->text(80),
            'created_by' => User::query()->first()->id,
        ];
    }
}
