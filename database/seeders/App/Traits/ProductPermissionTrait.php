<?php

namespace Database\Seeders\App\Traits;

trait ProductPermissionTrait
{
    public function product($type, $group = null): array
    {
        return [
            [
                'name' => 'products_manage',
                'type_id' => $type,
                'group_name' => $group ?? 'product'
            ],
            [
                'name' => 'view_products',
                'type_id' => $type,
                'group_name' => $group ?? 'product'
            ],
            [
                'name' => 'create_products',
                'type_id' => $type,
                'group_name' => $group ?? 'product'
            ],
            [
                'name' => 'update_products',
                'type_id' => $type,
                'group_name' => $group ?? 'product'
            ],
            [
                'name' => 'delete_products',
                'type_id' => $type,
                'group_name' => $group ?? 'product'
            ],
            [
                'name' => 'import_products',
                'type_id' => $type,
                'group_name' => $group ?? 'product'
            ],
            [
                'name' => 'change_status_products',
                'type_id' => $type,
                'group_name' => $group ?? 'product'
            ],
        ];
    }
}