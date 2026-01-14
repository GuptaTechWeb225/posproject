<?php

namespace Database\Seeders\App\Traits;

trait SuppliersPermissionTrait
{
    public function suppliers($type, $group = null): array
    {
        return [
            [
                'name' => 'view_suppliers',
                'type_id' => $type,
                'group_name' => $group ?? 'supplier'
            ],
            [
                'name' => 'create_suppliers',
                'type_id' => $type,
                'group_name' => $group ?? 'supplier'
            ],
            [
                'name' => 'update_suppliers',
                'type_id' => $type,
                'group_name' => $group ?? 'supplier'
            ],
            [
                'name' => 'delete_suppliers',
                'type_id' => $type,
                'group_name' => $group ?? 'supplier'
            ],
            [
                'name' => 'change_status_supplier',
                'type_id' => $type,
                'group_name' => $group ?? 'supplier'
            ],
        ];
    }
}