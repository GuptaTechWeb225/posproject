<?php

namespace Database\Seeders\App\Traits;

trait ExpenseAreasPermissionTrait
{
    public function expense_areas($type, $group = null): array
    {
        return [
            [
                'name' => 'view_expense_areas',
                'type_id' => $type,
                'group_name' => $group ?? 'expense_areas'
            ],
            [
                'name' => 'create_expense_areas',
                'type_id' => $type,
                'group_name' => $group ?? 'expense_areas'
            ],
            [
                'name' => 'update_expense_areas',
                'type_id' => $type,
                'group_name' => $group ?? 'expense_areas'
            ],
            [
                'name' => 'delete_expense_areas',
                'type_id' => $type,
                'group_name' => $group ?? 'expense_areas'
            ],
        ];
    }
}