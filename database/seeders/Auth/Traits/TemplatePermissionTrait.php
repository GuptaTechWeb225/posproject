<?php

namespace Database\Seeders\Auth\Traits;

trait TemplatePermissionTrait
{
    public function template($type, $group = null): array
    {
        return [
            [
                'name' => 'view_notification_templates',
                'type_id' => $type,
                'group_name' => $group ?? 'templates'
            ],
            [
                'name' => 'create_notification_templates',
                'type_id' => $type,
                'group_name' => $group ?? 'templates'
            ],
            [
                'name' => 'update_notification_templates',
                'type_id' => $type,
                'group_name' => $group ?? 'templates'
            ],
            [
                'name' => 'delete_notification_templates',
                'type_id' => $type,
                'group_name' => $group ?? 'templates'
            ],
            [
                'name' => 'view_templates',
                'type_id' => $type,
                'group_name' => $group ?? 'templates'
            ],
            [
                'name' => 'create_templates',
                'type_id' => $type,
                'group_name' => $group ?? 'templates'
            ],
            [
                'name' => 'update_templates',
                'type_id' => $type,
                'group_name' => $group ?? 'templates'
            ],
            [
                'name' => 'delete_templates',
                'type_id' => $type,
                'group_name' => $group ?? 'templates'
            ],
        ];
    }
}