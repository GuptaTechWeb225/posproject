<?php

namespace Database\Factories\Tenant\Supplier;

use App\Models\Core\Auth\User;
use App\Models\Tenant\Supplier\Supplier;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'supplier_no' => $this->faker->firstName,
            'status_id' => $this->faker->randomElement([...resolve(StatusRepository::class)->supplier()->pluck('id')->toArray()]),
            'created_by' => User::query()->first()->id,
        ];
    }
}
