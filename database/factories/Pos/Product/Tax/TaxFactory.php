<?php

namespace Database\Factories\Pos\Product\Tax;

use App\Models\Pos\Product\Tax\Tax;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tax::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Tax-'.$this->faker->name,
            'percentage' => $this->faker->randomNumber(2),
        ];
    }
}
