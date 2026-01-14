<?php

namespace Database\Factories\Tenant\Transaction;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Tenant\Branch\Branch;
use App\Models\Tenant\Customer\Customer;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\PaymentMethod\PaymentMethod;
use App\Models\Tenant\Transaction\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'payment_method_id' => PaymentMethod::query()->first()->id,
            'transaction_at' => $this->faker->dateTime,
            'transactionable_type' => Order::class,
            'transactionable_id' => $this->faker->randomElement([...Order::query()->pluck('id')]),
            'amount' => $this->faker->randomNumber(3),
            'created_by' => User::query()->first()->id

        ];
    }
}
