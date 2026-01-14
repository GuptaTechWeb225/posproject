<?php

namespace Database\Factories\Tenant\Expense;

use App\Models\Core\Auth\User;
use App\Models\Tenant\Expense\ExpenseArea;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseAreaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExpenseArea::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->colorName,
            'description' => $this->faker->text(),
            'is_add_to_report' => $this->faker->boolean(),
            'created_by' => User::query()->first()->id,
        ];
    }

}