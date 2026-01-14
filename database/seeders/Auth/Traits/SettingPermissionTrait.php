<?php

namespace Database\Seeders\Auth\Traits;

trait SettingPermissionTrait
{
    public function setting($type, $group = null): array
    {
        return [
            [
                'name' => 'view_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'settings'
            ],
            [
                'name' => 'update_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'settings'
            ],
            [
                'name' => 'update_delivery_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'settings'
            ],
            [
                'name' => 'view_delivery_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'settings'
            ],
            [
                'name' => 'view_brand_delivery_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'settings'
            ],
            [
                'name' => 'update_brand_privacy_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'settings'
            ],
            [
                'name' => 'view_brand_privacy_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'settings'
            ],
            [
                'name' => 'view_notification_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'settings'
            ],
            [
                'name' => 'update_notification_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'settings'
            ],
        ];
    }

}