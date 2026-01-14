<?php

namespace Database\Factories\Pos\Product\Brand;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Pos\Product\Brand\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class BrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Brand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Brand-'.$this->faker->colorName,
            'description' => $this->faker->text('80'),
            'created_by' => User::query()->first()->id,
        ];
    }
}
