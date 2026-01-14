<?php

namespace Database\Factories\Tenant\Customer;

use App\Models\Core\Auth\User;
use App\Models\Tenant\Customer\Customer;
use App\Models\Tenant\Customer\CustomerGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' =>  $this->faker->lastName,
            'email' =>  $this->faker->email,
            'tin' =>  $this->faker->randomNumber() ?? '82221',
            'customer_group_id' =>  CustomerGroup::query()->first()->id,
            'created_by' => User::query()->first()->id,
            'tenant_id' => 1
        ];
    }
}
