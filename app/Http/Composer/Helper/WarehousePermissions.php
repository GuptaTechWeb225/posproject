<?php


namespace App\Http\Composer\Helper;


use App\Helpers\Core\Traits\InstanceCreator;

class WarehousePermissions
{
    use InstanceCreator;

    public function permissions()
    {
        return [
            [
                'name' => __t('warehouse_stock'),
                'url' => route('support.warehouse.stock',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_stock'
                ]),
            ],
            [
                'name' => __t('lot_list'),
                'url' => route('support.Lot.list',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'manage_lot'
                ]),
            ],
            [
                'name' => __t('stock_transfer'),
                'url' => route('support.stock.transfer',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'stock_transfer'
                ]),
            ],
            [
                'name' => __t('warehouse'),
                'url' => route('support.warehouse.list',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_stock'
                ]),
            ],
        ];
    }

    public function canVisit()
    {
        return authorize_any([
            'view_stock', 'manage_lot', 'stock_adjustment',
            'import_stock', 'print_bar_code', 'internal_transfer',
        ]);
    }

}