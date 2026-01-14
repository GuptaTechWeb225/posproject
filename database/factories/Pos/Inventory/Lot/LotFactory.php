<?php

namespace Database\Factories\Pos\Inventory\Lot;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Pos\Inventory\Lot\Lot;
use App\Models\Tenant\Supplier\Supplier;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

class LotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    private static $lotNumber = 1;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {

        return [
            'supplier_id' => $this->faker->randomElement([...Supplier::query()->pluck('id')]),
            'status_id' => resolve(StatusRepository::class)->purchasePending(),
            'branch_or_warehouse_id' => $this->faker->randomElement([...BranchOrWarehouse::query()->pluck('id')]),
            'created_by' => $this->faker->randomElement([...User::query()->pluck('id')]),
            'other_charge' => $this->faker->numberBetween(50,150),
            'discount_amount' => $this->faker->numberBetween(10, 200),
            'purchase_date' => $this->faker->date,
            'adjusted_with_stock' => 0,
            'reference_no' => $this->faker->unique()->numberBetween(210002,265653),
            'note' => $this->faker->text(80)
        ];
    }
}
