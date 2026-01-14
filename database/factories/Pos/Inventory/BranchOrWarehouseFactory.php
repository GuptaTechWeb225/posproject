<?php

namespace Database\Factories\Pos\Inventory;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchOrWarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $types = ['branch', 'warehouse'];
        return [
            'name' => $this->faker->state,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->companyEmail,
            'manager_id' => User::query()->first()->id,
            'status_id' => Status::query()->where('name','status_active')->where('type','branchOrWarehouse')->first()->id,
            'location' => $this->faker->address,
            'tenant_id' => 1,
            'tax_id' => 3,
            'type'=> $types[array_rand($types)],

        ];
    }
}
