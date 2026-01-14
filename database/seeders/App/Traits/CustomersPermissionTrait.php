<?php

namespace Database\Seeders\App\Traits;

trait CustomersPermissionTrait
{
    public function customers($type, $group = null): array
    {
        return [
            [
                'name' => 'view_customers',
                'type_id' => $type,
                'group_name' => $group ?? 'customer'
            ],
            [
                'name' => 'create_customers',
                'type_id' => $type,
                'group_name' => $group ?? 'customer'
            ],
            [
                'name' => 'update_customers',
                'type_id' => $type,
                'group_name' => $group ?? 'customer'
            ],
            [
                'name' => 'delete_customers',
                'type_id' => $type,
                'group_name' => $group ?? 'customer'
            ],
            [
                'name' => 'details_customer',
                'type_id' => $type,
                'group_name' => $group ?? 'customer'
            ],
            [
                'name' => 'info_customer',
                'type_id' => $type,
                'group_name' => $group ?? 'customer'
            ],
        ];
    }
}