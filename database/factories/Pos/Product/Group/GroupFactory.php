<?php

namespace Database\Factories\Pos\Product\Group;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Pos\Product\Group\Group;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class GroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Group-'.$this->faker->colorName,
            'description' => $this->faker->text('80'),
            'created_by' => User::query()->first()->id,
            'status_id' => Status::query()->where('name','status_active')->where('type','group')->first()->id,
        ];
    }
}
