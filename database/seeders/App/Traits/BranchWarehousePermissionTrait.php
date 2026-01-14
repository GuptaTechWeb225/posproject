<?php

namespace Database\Seeders\App\Traits;

trait BranchWarehousePermissionTrait
{
    public function branchWarehouse($type, $group = null): array
    {
        return [
            [
                'name' => 'view_branch_or_warehouses',
                'type_id' => $type,
                'group_name' => $group ?? 'switch_branch'
            ],
        ];
    }

}