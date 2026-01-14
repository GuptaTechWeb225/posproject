<?php

namespace Database\Seeders\App\Traits;

trait SubCategoryPermissionTrait
{
    public function subcategory($type, $group = null): array
    {
        return [
            [
                'name' => 'view_sub_categories ',
                'type_id' => $type,
                'group_name' => $group ?? 'sub_category'
            ],
            [
                'name' => 'create_sub_categories',
                'type_id' => $type,
                'group_name' => $group ?? 'sub_category'
            ],
            [
                'name' => 'update_sub_categories',
                'type_id' => $type,
                'group_name' => $group ?? 'sub_category'
            ],
            [
                'name' => 'delete_sub_categories',
                'type_id' => $type,
                'group_name' => $group ?? 'sub_category'
            ],
            [
                'name' => 'change_status_subcategory',
                'type_id' => $type,
                'group_name' => $group ?? 'sub_category'
            ],
        ];
    }
}