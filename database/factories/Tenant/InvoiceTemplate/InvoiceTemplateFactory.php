<?php

namespace Database\Factories\Tenant\InvoiceTemplate;

use App\Models\Tenant\InvoiceTemplate\InvoiceTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceTemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceTemplate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Default',
            'default_content' => 'Default',
            'custom_content' => 'Default',
            'type' => 'sales_invoice',
            'is_default' => 1,
            'created_by' => 1,
        ];
    }
}
