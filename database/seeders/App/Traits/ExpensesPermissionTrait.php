<?php

namespace Database\Seeders\App\Traits;

trait ExpensesPermissionTrait
{
    public function expense($type, $group = null): array
    {
        return [
            [
                'name' => 'view_expenses',
                'type_id' => $type,
                'group_name' => $group ?? 'expense'
            ],
            [
                'name' => 'create_expenses',
                'type_id' => $type,
                'group_name' => $group ?? 'expense'
            ],
            [
                'name' => 'update_expenses',
                'type_id' => $type,
                'group_name' => $group ?? 'expense'
            ],
            [
                'name' => 'delete_expenses',
                'type_id' => $type,
                'group_name' => $group ?? 'expense'
            ],
        ];
    }
}