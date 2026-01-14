<?php

namespace Database\Seeders\App\Traits;

trait SalesReturnPermissionTrait
{
    public function sales_return($type, $group = null): array
    {
        return [
            [
                'name' => 'create_return_order',
                'type_id' => $type,
                'group_name' => $group ?? 'sales_return'
            ],
            [
                'name' => 'view_order_return',
                'type_id' => $type,
                'group_name' => $group ?? 'sales_return'
            ],
        ];
    }
}