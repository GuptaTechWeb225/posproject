<?php


namespace App\Http\Composer\Helper;


use App\Helpers\Core\Traits\InstanceCreator;

class ContactPermissions
{
    use InstanceCreator;

    public function permissions()
    {
        return [
            [
                'name' => __t('customers'),
                'url' => route('support.customer.lists', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any([
                    'view_customers',
                    'create_customers',
                    'update_customers',
                    'delete_customers',
                    'details_customer'
                ]),
            ],
            [
                'name' => ucwords(__t('customer_groups')),
                'url' => route('support.customer.group.lists', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any([
                    'view_customer_groups',
                    'create_customer_groups',
                    'update_customer_groups',
                    'delete_customer_groups'
                ]),
            ],
            [
                'name' => __t('suppliers'),
                'url' => route('support.supplier.lists', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any([
                    'view_suppliers',
                    'create_suppliers',
                    'update_suppliers',
                    'delete_suppliers',
                ]),
            ],

        ];
    }

    //used from parent file .
    public function canVisit(): bool
    {
        return authorize_any([
            'view_customer_groups',
            'view_customers',
            'view_suppliers'
        ]);
    }
}
