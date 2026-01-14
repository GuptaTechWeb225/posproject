<?php

namespace Database\Seeders\App\Traits;

trait GroupPermissionTrait
{
    public function group($type, $group = null): array
    {
        return [
            [
                'name' => 'view_groups',
                'type_id' => $type,
                'group_name' => $group ?? 'group'
            ],
            [
                'name' => 'create_groups',
                'type_id' => $type,
                'group_name' => $group ?? 'group'
            ],
            [
                'name' => 'update_groups',
                'type_id' => $type,
                'group_name' => $group ?? 'group'
            ],
            [
                'name' => 'delete_groups',
                'type_id' => $type,
                'group_name' => $group ?? 'group'
            ],
        ];
    }
}