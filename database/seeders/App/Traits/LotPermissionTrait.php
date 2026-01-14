<?php

namespace Database\Seeders\App\Traits;

trait LotPermissionTrait
{
    public function lot($type, $group = null ): array
    {
        return [
            [
                'name' => 'lot_manage', //navigation route file
                'type_id' => $type,
                'group_name' => $group ?? 'lot'
            ],
            [
                'name' => 'view_lot',
                'type_id' => $type,
                'group_name' => $group ?? 'lot'
            ],
            [
                'name' => 'create_lot',
                'type_id' => $type,
                'group_name' => $group ?? 'lot'
            ],
            [
                'name' => 'update_lot',
                'type_id' => $type,
                'group_name' => $group ?? 'lot'
            ],
        ];
    }
}