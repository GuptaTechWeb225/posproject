<?php

namespace Database\Factories\Pos\Inventory;

use App\Models\Core\Status;
use App\Models\Pos\Inventory\InternalTransfer;
use App\Models\Pos\Product\Product\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

class InternalTransferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InternalTransfer::class;
    private static $lotNumber = 1;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'variant_id' =>  $this->faker->randomElement([...Variant::query()->pluck('id')]),
            'status_id' => $this->faker->randomElement([...Status::query()->pluck('id')]),
            'date' => $this->faker->date(),
            'note' =>$this->faker->text(80),
        ];
    }
}
