<?php

namespace Database\Factories\Pos\Product\Product;

use App\Models\Core\Auth\User;
use App\Models\Pos\Product\Attribute\AttributeVariation;
use App\Models\Pos\Product\Product\Product;
use App\Models\Pos\Product\Product\Variant;
use App\Models\Pos\Product\Tax\Tax;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

class VariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Variant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => $this->faker->randomElement([...Product::query()->pluck('id')]),
            'tax_id' => $this->faker->randomElement([...Tax::query()->pluck('id')]),
            'upc' => $this->faker->randomNumber(6),
            'selling_price' => $this->faker->randomNumber(6),
            'status_id' => $this->faker->randomElement([...resolve(StatusRepository::class)->product()->pluck('id')->toArray()]),
            'created_by' => User::query()->first()->id,
            'name' => $this->faker->colorName, //this is variant name
            'description' => $this->faker->colorName, //this is variant name
            'stock_reminder_quantity' => $this->faker->randomNumber(2),
        ];
    }

    private function variantName()
    {
        return $this->faker->randomElement([...Product::query()->pluck('name')])
            .'-'.
            $this->faker->randomElement([...AttributeVariation::query()->pluck('name')])
            .'-'.
            $this->faker->randomElement([...AttributeVariation::query()->pluck('name')]);
    }
}
