<?php

namespace Database\Seeders\Auth\Traits;

trait UserPermissionTrait
{
    public function user($type, $group = null): array
    {
        return [
            [
                'name' => 'view_users',
                'type_id' => $type,
                'group_name' => $group ?? 'users'
            ],
            [
                'name' => 'create_users',
                'type_id' => $type,
                'group_name' => $group ?? 'users'
            ],
            [
                'name' => 'update_users',
                'type_id' => $type,
                'group_name' => $group ?? 'users'
            ],
            [
                'name' => 'delete_users',
                'type_id' => $type,
                'group_name' => $group ?? 'users'
            ],
            [
                'name' => 'cancel_user_invitation',
                'type_id' => $type,
                'group_name' => $group ?? 'users'
            ],
            [
                'name' => 'attach_roles_users',
                'type_id' => $type,
                'group_name' => $group ?? 'users'
            ],
            [
                'name' => 'detach_roles_users',
                'type_id' => $type,
                'group_name' => $group ?? 'users'
            ],
            [
                'name' => 'change_settings_users',
                'type_id' => $type,
                'group_name' => $group ?? 'users'
            ],
            [
                'name' => 'settings_list_users',
                'type_id' => $type,
                'group_name' => $group ?? 'users',
            ],
            [
                'name' => 'invite_user',
                'type_id' => $type,
                'group_name' => $group ?? 'users'
            ],
            [
                'name' => 'created_users',
                'type_id' => $type,
                'group_name' => $group ?? 'users'
            ],
        ];
    }
}