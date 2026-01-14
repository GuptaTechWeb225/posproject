<?php

namespace Database\Seeders\App\Traits;

trait BrandPermissionTrait
{
    public function brand($type, $group = null): array
    {
        return [
            [
                'name' => 'view_brands',
                'type_id' => $type,
                'group_name' => $group ?? 'brand'
            ],
            [
                'name' => 'create_brands',
                'type_id' => $type,
                'group_name' => $group ?? 'brand'
            ],
            [
                'name' => 'update_brands',
                'type_id' => $type,
                'group_name' => $group ?? 'brand'
            ],
            [
                'name' => 'delete_brands',
                'type_id' => $type,
                'group_name' => $group ?? 'brand'
            ],
            [
                'name' => 'export_brands',
                'type_id' => $type,
                'group_name' => $group ?? 'brand'
            ],
        ];
    }
}