<?php

namespace Database\Seeders\App\Traits;

trait CategoryPermissionTrait
{
    public function category($type, $group = null): array
    {
        return [
            [
                'name' => 'categories_manage',
                'type_id' => $type,
                'group_name' => $group ?? 'category'
            ],
            [
                'name' => 'view_categories',
                'type_id' => $type,
                'group_name' => $group ?? 'category'
            ],
            [
                'name' => 'create_categories',
                'type_id' => $type,
                'group_name' => $group ?? 'category'
            ],
            [
                'name' => 'update_categories',
                'type_id' => $type,
                'group_name' => $group ?? 'category'
            ],
            [
                'name' => 'delete_categories',
                'type_id' => $type,
                'group_name' => $group ?? 'category'
            ],
            [
                'name' => 'change_category_status',
                'type_id' => $type,
                'group_name' => $group ?? 'category'
            ],
        ];
    }
}