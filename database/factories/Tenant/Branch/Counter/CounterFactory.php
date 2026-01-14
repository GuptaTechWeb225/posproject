<?php

namespace Database\Factories\Tenant\Branch\Counter;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Tenant\Branch\Branch;
use App\Models\Tenant\Branch\Counter\Counter;
use App\Models\Tenant\InvoiceTemplate\InvoiceTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class CounterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Counter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        ];
    }
}
