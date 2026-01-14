<?php

namespace Database\Factories\Tenant\Order;

use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Order\OrderProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id' => $this->faker->randomElement([...Order::query()->pluck('id')]),
            'stock_id' => $this->faker->randomElement([...Stock::query()->pluck('id')]),
            'price' => $this->faker->randomNumber(3),
            'quantity' => $this->faker->randomNumber(2),
            'tax_type' => $this->faker->randomElement(['inclusive', 'exclusive']),
            'tax_amount' => $this->faker->randomNumber(2),
            'discount' => $this->faker->randomNumber(1),
            'sub_total' => $this->faker->randomNumber(2),
            'note' => $this->faker->text(15),
        ];
    }
}
