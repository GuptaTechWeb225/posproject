<?php

namespace Database\Seeders\Customer;

use App\Models\Core\Auth\User;
use App\Models\Tenant\Customer\Customer;
use App\Models\Tenant\Customer\CustomerGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customerGroup = CustomerGroup::create([
            'name' => 'Walk-in-group',
            'is_default' => 1,
            'discount_id' => null,
            'tenant_id' => 1
        ]);
        Customer::create([
            'customer_group_id' => $customerGroup->id,
            'first_name' => 'Walk-in',
            'last_name' => '-customer',
            'email' => 'walkincustomer@gmail.com', //must needed an email
            'tin' => 1234567,
            'created_by' => User::find(1)->id,
            'tenant_id' => 1
        ]);
    }
}
