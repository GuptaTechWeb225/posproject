<?php


namespace App\Http\Composer\Helper;


use App\Helpers\Core\Traits\InstanceCreator;

class SettingPermissions
{
    use InstanceCreator;

    public function permissions(): array
    {
        return [
            [
                'name' => __t('app_settings'),
                'url' => route('support.tenant.settings', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_settings',
                    'view_notification_settings',
                    'check_for_updates',
                    'view_delivery_settings'
                ])
            ],
            [
                'name' => __t('pos_settings'),
                'url' => route('support.tenant.pos-settings', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_pos_settings',
                ])
            ],
            [
                'name' => __t('branch_&_warehouses'),
                'url' => route('support.branchAndWarehouse.lists', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_branch',
                    'create_branch',
                    'update_branch',
                    'delete_branch'
                ])
            ],
            [
                'name' => __t('counter'),
                'url' => route('support.counters', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_counter',
                ])
            ],
            [
                'name' => __t('tax_management'),
                'url' => route('support.tax-managements', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_tax_management'
                ])
            ],
            [
                'name' => __t('discount'),
                'url' => route('support.discounts', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_discount'
                ])
            ],
        ];
    }

    public function canVisit()
    {
        return authorize_any([
            'view_settings',
            'view_notification_settings',
            'check_for_updates',
            'view_delivery_settings',
            'view_tax_management',

            'view_branch',
            'create_branch',
            'update_branch',
            'delete_branch',
            'view_pos_settings',
            'view_discount',

            'view_counter'
        ]);
    }
}
