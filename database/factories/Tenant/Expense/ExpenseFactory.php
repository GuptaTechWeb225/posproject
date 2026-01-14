<?php

namespace Database\Factories\Tenant\Expense;

use App\Models\Core\Auth\User;
use App\Models\Tenant\Expense\Expense;
use App\Models\Tenant\Expense\ExpenseArea;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Expense::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'expense_area_id' => ExpenseArea::query()->first()->id,
            'name' => $this->faker->colorName,
            'amount' => $this->faker->randomNumber(3),
            'description' => $this->faker->realText(),
            'created_by' => User::query()->first()->id,
        ];
    }

}