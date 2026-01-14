<?php

namespace Database\Factories\Tenant\SmsTemplate;

use App\Models\Tenant\SmsTemplate\SmsTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmsTemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmsTemplate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => $this->faker->title,
            'is_default' => 1,
        ];
    }
}
