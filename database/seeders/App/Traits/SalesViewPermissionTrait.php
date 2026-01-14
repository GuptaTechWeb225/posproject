<?php

namespace Database\Seeders\App\Traits;

trait SalesViewPermissionTrait
{
    public function sales_view($type, $group = null): array
    {
        return [
            [
                'name' => 'view_sales',
                'type_id' => $type,
                'group_name' => $group ?? 'sales_view'
            ],
        ];
    }
}