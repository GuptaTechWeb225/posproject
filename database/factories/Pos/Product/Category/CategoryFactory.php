<?php

namespace Database\Factories\Pos\Product\Category;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Pos\Product\Category\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use phpDocumentor\Reflection\Types\This;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Category-'.$this->faker->colorName,
            'description' =>  $this->faker->text('80'),
            'created_by' => User::query()->first()->id,
            'status_id' => Status::query()->where('name','status_active')->where('type','category')->first()->id,
            'tenant_id' => 1
        ];
    }
}
