<?php

namespace Database\Seeders\Tax;

use App\Models\Pos\Product\Tax\Tax;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    public function run()
    {
        $taxes = [
            [
                'name' => 'Default tax',
                'percentage' => 0,
                'is_default' => true,
            ],
            [
                'name' => 'Zero tax',
                'percentage' => 0,
                'is_default' => false,
            ],
            [
                'name' => '5% tax',
                'percentage' => 5,
                'is_default' => false,
            ],
            [
                'name' => '10% tax',
                'percentage' => 10,
                'is_default' => false,
            ],
            [
                'name' => '15% tax',
                'percentage' => 15,
                'is_default' => false
            ]
        ];

        Tax::query()->insert($taxes);
    }
}