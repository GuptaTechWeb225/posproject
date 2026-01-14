<?php

namespace Database\Seeders\Tenant;

use App\Models\Core\Auth\User;
use App\Models\Pos\Product\Attribute\Attribute;
use Illuminate\Database\Seeder;

class AttributeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Color',
                'created_by' => User::query()->first()->id
            ],
            [
                'name' => 'Size',
                'created_by' => User::query()->first()->id
            ]
        ];
        Attribute::query()->insert($data);
    }
}
