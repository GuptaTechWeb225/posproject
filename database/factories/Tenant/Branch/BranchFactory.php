<?php

namespace Database\Factories\Tenant\Branch;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Tenant\Branch\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Branch::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->state,
            'manager_id' => User::query()->first()->id,
            'status_id' => Status::query()->where('name','status_active')->where('type','branch')->first()->id,
            'location' => $this->faker->address,
        ];
    }
}
