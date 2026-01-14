<?php

namespace Database\Factories\Pos\Product\SubCategory;

use App\Models\Core\Status;
use App\Models\Pos\Product\Category\Category;
use App\Models\Pos\Product\SubCategory\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class SubCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {


        return [
            'name' => 'Subcategory-'.$this->faker->name,
            'description' => $this->faker->text(80),
            'created_by' => 1,
            'status_id' => Status::query()->where('name','status_active')->where('type','sub_category')->first()->id,
            'category_id' => $this->faker->randomElement(Category::query()->pluck('id')->toArray()),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
