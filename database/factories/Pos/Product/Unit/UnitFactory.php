<?php

namespace Database\Factories\Pos\Product\Unit;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Pos\Product\Unit\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Unit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $unit = 'Unit-'.$this->faker->colorName;
        return [
            'name' => $unit,
            'short_name' => Str::limit($unit, 5),
            'created_by' => User::query()->first()->id,
            'status_id' => Status::query()->where('name','status_active')->where('type','unit')->first()->id,
        ];
    }
}
