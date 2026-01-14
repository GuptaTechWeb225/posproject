<?php

namespace Database\Seeders\Status;

use App\Models\Core\Status;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        Status::query()->truncate();
        $statuses = [
            [
                'name' => 'status_active',
                'type' => 'user',
                'class' => 'success'
            ],
            [
                'name' => 'status_inactive',
                'type' => 'user',
                'class' => 'danger'
            ],
            [
                'name' => 'status_invited',
                'type' => 'user',
                'class' => 'purple'
            ],
            [
                'name' => 'status_active',
                'type' => 'branch',
                'class' => 'success'
            ],
            [
                'name' => 'status_inactive',
                'type' => 'branch',
                'class' => 'danger'
            ],
            [
                'name' => 'status_active',
                'type' => 'branchorwarehouse',
                'class' => 'success'
            ],
            [
                'name' => 'status_inactive',
                'type' => 'branchorwarehouse',
                'class' => 'danger'
            ],
            [
                'name' => 'status_open',
                'type' => 'counter',
                'class' => 'success'
            ],
            [
                'name' => 'status_close',
                'type' => 'counter',
                'class' => 'danger'
            ],
            [
                'name' => 'status_active',
                'type' => 'payment_method',
                'class' => 'success'
            ],
            [
                'name' => 'status_inactive',
                'type' => 'payment_method',
                'class' => 'danger'
            ],

            [
                'name' => 'status_pending',
                'type' => 'order',
                'class' => 'primary'
            ],
            [
                'name' => 'status_hold',
                'type' => 'order',
                'class' => 'warning'
            ],
            [
                'name' => 'status_done',
                'type' => 'order',
                'class' => 'success'
            ],
            [
                'name' => 'status_due',
                'type' => 'order',
                'class' => 'danger'
            ],
            [
                'name' => 'status_cancel',
                'type' => 'order',
                'class' => 'danger'
            ],
            [
                'name' => 'status_return',
                'type' => 'order',
                'class' => 'warning'
            ],
            [
                'name' => 'status_partial_return',
                'type' => 'order',
                'class' => 'danger'
            ],
            [
                'name' => 'status_active',
                'type' => 'product',
                'class' => 'success'
            ],
            [
                'name' => 'status_inactive',
                'type' => 'product',
                'class' => 'danger'
            ],
            [
                'name' => 'status_active',
                'type' => 'brand',
                'class' => 'success'
            ],
            [
                'name' => 'status_inactive',
                'type' => 'brand',
                'class' => 'danger'
            ],
            [
                'name' => 'status_active',
                'type' => 'unit',
                'class' => 'success'
            ],
            [
                'name' => 'status_inactive',
                'type' => 'unit',
                'class' => 'danger'
            ],
            [
                'name' => 'status_active',
                'type' => 'group',
                'class' => 'success'
            ],
            [
                'name' => 'status_inactive',
                'type' => 'group',
                'class' => 'danger'
            ],
            [
                'name' => 'status_active',
                'type' => 'category',
                'class' => 'success'
            ],
            [
                'name' => 'status_inactive',
                'type' => 'category',
                'class' => 'danger'
            ],
            [
                'name' => 'status_active',
                'type' => 'sub_category',
                'class' => 'success'
            ],
            [
                'name' => 'status_inactive',
                'type' => 'sub_category',
                'class' => 'danger'
            ],
            [
                'name' => 'status_active',
                'type' => 'tag',
                'class' => 'success'
            ],
            [
                'name' => 'status_inactive',
                'type' => 'tag',
                'class' => 'danger'
            ],
            [
                'name' => 'status_active',
                'type' => 'duration',
                'class' => 'success'
            ],
            [
                'name' => 'status_inactive',
                'type' => 'duration',
                'class' => 'danger'
            ],
            [
                'name' => 'status_active',
                'type' => 'supplier',
                'class' => 'success'
            ],
            [
                'name' => 'status_inactive',
                'type' => 'supplier',
                'class' => 'danger'
            ],
            [
                'name' => 'status_pending',
                'type' => 'purchase',
                'class' => 'warning'
            ],
            [
                'name' => 'status_delivered',
                'type' => 'purchase',
                'class' => 'success'
            ],
            [
                'name' => 'status_declined',
                'type' => 'purchase',
                'class' => 'danger'
            ],
            [
                'name' => 'status_active',
                'type' => 'discount',
                'class' => 'success'
            ],
            [
                'name' => 'status_inactive',
                'type' => 'discount',
                'class' => 'danger'
            ],
            [
                'name' => 'status_pending',
                'type' => 'internaltransfer',
                'class' => 'warning'
            ],
            [
                'name' => 'status_complete',
                'type' => 'internaltransfer',
                'class' => 'success'
            ],
            [
                'name' => 'status_failed',
                'type' => 'internaltransfer',
                'class' => 'danger'
            ],
            [
                'name' => 'status_open',
                'type' => 'cash_counter',
                'class' => 'success'
            ],
            [
                'name' => 'status_close',
                'type' => 'cash_counter',
                'class' => 'danger'
            ],
        ];

        Status::query()->insert($statuses);

        $this->enableForeignKeys();
    }
}
