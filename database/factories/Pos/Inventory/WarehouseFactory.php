<?php

namespace Database\Factories\Pos\Inventory;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Pos\Inventory\Warehouse;
use App\Models\Tenant\Branch\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Warehouse::class;
    private static $lotNumber = 1;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'name' => 'Warehouse'.' '.self::$lotNumber++,
            'status_id' => Status::query()->first()->id,
            'created_by' => User::query()->first()->id,
        ];
    }
}
