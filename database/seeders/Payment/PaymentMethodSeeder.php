<?php

namespace Database\Seeders\Payment;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Tenant\PaymentMethod\PaymentMethod;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = [
            [
                'name' => 'Cash',
                'alias' => 'cash',
                'rounded_to' => 'none',
                'is_default' => 1,
                'is_for_client' => 1,
                'status_id' => Status::query()
                    ->where('type', 'payment_method')
                    ->where('name', 'status_active')
                    ->first()->id,
                'created_by' => User::query()->first()->id
            ],
            [
                'name' => 'Credit',
                'alias' => 'credit',
                'rounded_to' => 'none',
                'is_default' => 0,
                'is_for_client' => 1,
                'status_id' => Status::query()
                    ->where('type', 'payment_method')
                    ->where('name', 'status_active')
                    ->first()->id,
                'created_by' => User::query()->first()->id
            ]
        ];
        PaymentMethod::insert($paymentMethods);
    }
}
