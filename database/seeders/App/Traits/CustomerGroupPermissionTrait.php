<?php

namespace Database\Seeders\App\Traits;

trait CustomerGroupPermissionTrait
{
    public function customer_group($type, $group = null): array
    {
        return [
            [
                'name' => 'view_customer_groups',
                'type_id' => $type,
                'group_name' => $group ?? 'customer_group'
            ],
            [
                'name' => 'create_customer_groups',
                'type_id' => $type,
                'group_name' => $group ?? 'customer_group'
            ],
            [
                'name' => 'update_customer_groups',
                'type_id' => $type,
                'group_name' => $group ?? 'customer_group'
            ],
            [
                'name' => 'delete_customer_groups',
                'type_id' => $type,
                'group_name' => $group ?? 'customer_group'
            ],
        ];
    }
}