<?php

namespace Database\Factories\Pos\Inventory\Stock;


use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Pos\Inventory\Stock\Stock;
use App\Models\Pos\Product\Product\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Stock::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'variant_id' => $this->faker->randomElement([...Variant::query()->pluck('id')]),
            'branch_or_warehouse_id' => $this->faker->randomElement([...BranchOrWarehouse::query()->pluck('id')]),
            'avg_purchase_price' => $this->faker->randomNumber(4),
            'total_purchase_qty' => $this->faker->randomNumber(4),
            'total_sales_qty' => $this->faker->randomNumber(5),
            'total_adjustment_qty' => $this->faker->randomNumber(5),
            'total_internal_transfer_sent_qty' => $this->faker->randomNumber(5),
            'total_internal_transfer_received_qty' => $this->faker->randomNumber(5),
            'available_qty' => $this->faker->randomNumber(5),
        ];
    }
}
