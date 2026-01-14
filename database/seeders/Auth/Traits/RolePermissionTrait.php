<?php

namespace Database\Seeders\Auth\Traits;

trait RolePermissionTrait
{
    public function role($type, $group = null): array
    {
        return [
            [
                'name' => 'view_roles',
                'type_id' => $type,
                'group_name' => $group ?? 'roles'
            ],
            [
                'name' => 'create_roles',
                'type_id' => $type,
                'group_name' => $group ?? 'roles'
            ],
            [
                'name' => 'update_roles',
                'type_id' => $type,
                'group_name' => $group ?? 'roles'
            ],
            [
                'name' => 'delete_roles',
                'type_id' => $type,
                'group_name' => $group ?? 'roles'
            ],
            [
                'name' => 'attach_users_to_roles',
                'type_id' => $type,
                'group_name' => $group ?? 'roles'
            ],
            [
                'name' => 'detach_permissions_roles',
                'type_id' => $type,
                'group_name' => $group ?? 'permissions'
            ],
        ];
    }

}