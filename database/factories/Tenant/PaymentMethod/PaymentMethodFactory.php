<?php

namespace Database\Factories\Tenant\PaymentMethod;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Tenant\PaymentMethod\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Cash',
            'alias' => 'cash',
            'rounded_to' => $this->faker->randomElement(['none','nearest_integer','nearest_half']),
            'is_default' => 1,
            'is_for_client' => 1,
            'status_id' => Status::query()
                ->where('type','payment_method')
                ->where('name','status_active')
                ->first()->id,
            'created_by' => User::query()->first()->id
        ];
    }
}
