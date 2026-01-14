<?php

namespace Database\Factories\Tenant\Customer;

use App\Models\Tenant\Customer\CustomerGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => 'Default',
            'is_default' => 1,
            'tenant_id' => 1
        ];
    }
}
