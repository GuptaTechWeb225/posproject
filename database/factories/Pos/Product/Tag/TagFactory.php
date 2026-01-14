<?php

namespace Database\Factories\Pos\Product\Tag;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Pos\Product\Tag\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Tag-'.$this->faker->title,
            'color' => $this->faker->hexColor,
            'created_by' => User::query()->first()->id,
            'status_id' => Status::query()->where('name','status_active')->where('type','tag')->first()->id,
        ];
    }
}
