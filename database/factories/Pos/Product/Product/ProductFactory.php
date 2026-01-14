<?php

namespace Database\Factories\Pos\Product\Product;

use App\Models\Core\Auth\User;
use App\Models\Pos\Product\Brand\Brand;
use App\Models\Pos\Product\Category\Category;
use App\Models\Pos\Product\Duration\Duration;
use App\Models\Pos\Product\Group\Group;
use App\Models\Pos\Product\Product\Product;
use App\Models\Pos\Product\SubCategory\SubCategory;
use App\Models\Pos\Product\Unit\Unit;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => 'Product-'.$this->faker->streetName,
            'duration_id' => 1,
            'category_id' => $this->faker->randomElement([...Category::query()->pluck('id')]),
            'sub_category_id' => $this->faker->randomElement([...SubCategory::query()->pluck('id')]),
            'brand_id' => $this->faker->randomElement([...Brand::query()->pluck('id')]),
            'group_id' => $this->faker->randomElement([...Group::query()->pluck('id')]),
            'unit_id' => $this->faker->randomElement([...Unit::query()->pluck('id')]),
            'product_type' => $this->faker->randomElement([...['single','variant']]),
            'created_by' => User::query()->first()->id,
            'status_id' => $this->faker->randomElement([...resolve(StatusRepository::class)->product()->pluck('id')->toArray()]),
            'warranty_duration' => 10,
            'slug' => $this->faker->slug,
            'description' => $this->faker->paragraph,
            'note' => $this->faker->paragraph,
        ];
    }
}
