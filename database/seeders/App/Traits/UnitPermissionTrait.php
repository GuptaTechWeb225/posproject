<?php

namespace Database\Seeders\App\Traits;

trait UnitPermissionTrait
{
    public function unit($type, $group = null): array
    {
        return [
            [
                'name' => 'view_unit',
                'type_id' => $type,
                'group_name' => $group ?? 'unit'
            ],
            [
                'name' => 'create_unit',
                'type_id' => $type,
                'group_name' => $group ?? 'unit'
            ],
            [
                'name' => 'update_unit',
                'type_id' => $type,
                'group_name' => $group ?? 'unit'
            ],
            [
                'name' => 'delete_unit',
                'type_id' => $type,
                'group_name' => $group ?? 'unit'
            ],
            [
                'name' => 'change_unit_status',
                'type_id' => $type,
                'group_name' => $group ?? 'unit'
            ],
            [
                'name' => 'export_units',
                'type_id' => $type,
                'group_name' => $group ?? 'unit'
            ],
        ];
    }
}