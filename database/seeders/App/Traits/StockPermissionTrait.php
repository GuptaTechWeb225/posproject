<?php

namespace Database\Seeders\App\Traits;

trait StockPermissionTrait
{
    public function stock($type, $group = null): array
    {
        return [
            [
                'name' => 'view_stock',
                'type_id' => $type,
                'group_name' => $group ?? 'stock'
            ],
            [
                'name' => 'import_stock',
                'type_id' => $type,
                'group_name' => $group ?? 'stock'
            ],
            [
                'name' => 'view_stock_overview',
                'type_id' => $type,
                'group_name' => $group ?? 'stock'
            ],
        ];
    }
}