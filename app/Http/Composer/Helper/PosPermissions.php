<?php


namespace App\Http\Composer\Helper;


use App\Helpers\Core\Traits\InstanceCreator;

class PosPermissions
{
    use InstanceCreator;

    public function permissions()
    {
        return [
            [
                'name' => __t('sales_view'),
                'url' => route('support.sales.view',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_sales'
                ]),
            ],
            [
                'name' => __t('returns'),
                'url' => route('support.returns',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),//
                'permission' => authorize_any([
                    'view_order_return', 'create_return_order'
                ]),
            ],
            [
                'name' => __t('invoice'),
                'url' => route('support.invoice',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'list_invoice'
                ]),
            ]
        ];
    }

    public function canVisit(): bool
    {
        return authorize_any([
            'view_sales', 'create_return_order', 'view_sale_return',
            'view_order_return', 'list_invoice'
        ]);
    }
}
