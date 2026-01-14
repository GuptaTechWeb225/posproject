<?php

namespace Database\Factories\Tenant\Order;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Tenant\Branch\Branch;
use App\Models\Tenant\Customer\Customer;
use App\Models\Tenant\Order\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $lastInvoiceNumber = 10000;
        return [
            'type' => $this->faker->randomElement(['purchase', 'sale', 'transfer']),
            'invoice_number' => 'POS-'.++$lastInvoiceNumber,
            'last_invoice_number' => $lastInvoiceNumber,
            'branch_id' => Branch::query()->first()->id,
            'status_id' => $this->faker->randomElement([...Status::query()->where('type', 'order')->pluck('id')]),
            'ordered_at' => $this->faker->dateTime(),
            'sub_total' => $this->faker->randomNumber(4),
            'total_tax' => $this->faker->randomNumber(4),
            'discount' => $this->faker->randomNumber(4),
            'note' => $this->faker->text(30),
            'receivable_type' => 'App\Models\Customer' ,
            'receivable_id' => Customer::query()->first()->id,
            'created_by' => User::query()->first()->id

        ];
    }
}
