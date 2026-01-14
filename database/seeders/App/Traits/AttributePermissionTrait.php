<?php

namespace Database\Seeders\App\Traits;

trait AttributePermissionTrait
{
    public function attribute($type, $group = null): array
    {
        return [
            [
                'name' => 'view_attributes',
                'type_id' => $type,
                'group_name' => $group ?? 'attribute'
            ],
            [
                'name' => 'create_attributes',
                'type_id' => $type,
                'group_name' => $group ?? 'attribute'
            ],
            [
                'name' => 'update_attributes',
                'type_id' => $type,
                'group_name' => $group ?? 'attribute'
            ],
            [
                'name' => 'delete_attributes',
                'type_id' => $type,
                'group_name' => $group ?? 'attribute'
            ],
            [
                'name' => 'import_attributes',
                'type_id' => $type,
                'group_name' => $group ?? 'attribute'
            ],
            [
                'name' => 'export_attributes',
                'type_id' => $type,
                'group_name' => $group ?? 'attribute'
            ]
        ];
    }
}