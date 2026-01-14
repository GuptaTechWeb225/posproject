<?php

namespace Database\Seeders\App\Traits;

trait AddressPermissionTrait
{
    public function addresses($type, $group = null): array
    {
        return [
            [
                'name' => 'view_address',
                'type_id' => $type,
                'group_name' => $group ?? 'addresses'
            ],
            [
                'name' => 'create_address',
                'type_id' => $type,
                'group_name' => $group ?? 'addresses'
            ],
            [
                'name' => 'update_address',
                'type_id' => $type,
                'group_name' => $group ?? 'addresses'
            ],
            [
                'name' => 'delete_address',
                'type_id' => $type,
                'group_name' => $group ?? 'addresses'
            ],
            [
                'name' => 'areas_address',
                'type_id' => $type,
                'group_name' => $group ?? 'addresses'
            ],
            [
                'name' => 'areas_city',
                'type_id' => $type,
                'group_name' => $group ?? 'addresses'
            ],
        ];
    }
}