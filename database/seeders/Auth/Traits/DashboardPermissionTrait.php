<?php

namespace Database\Seeders\Auth\Traits;

trait DashboardPermissionTrait
{
    public function dashboard($type, $group = null): array
    {
        return [
            [
                'name' => 'dashboard_tenant',
                'type_id' => $type,
                'group_name' => $group ?? 'dashboard'
            ],
        ];
    }
}