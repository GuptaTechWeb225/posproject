<?php

namespace Database\Seeders\App\Traits;

trait StockAdjustmentPermission
{
    public function stockAdjustment($type, $group = null): array
    {
        return [
            [
                'name' => 'view_stock_adjustments',
                'type_id' => $type,
                'group_name' => $group ?? 'stock_adjustment'
            ],
            [
                'name' => 'create_stock_adjustments',
                'type_id' => $type,
                'group_name' => $group ?? 'stock_adjustment'
            ],
            [
                'name' => 'update_stock_adjustments',
                'type_id' => $type,
                'group_name' => $group ?? 'stock_adjustment'
            ],
            [
                'name' => 'delete_stock_adjustments',
                'type_id' => $type,
                'group_name' => $group ?? 'stock_adjustment'
            ]
        ];
    }
}