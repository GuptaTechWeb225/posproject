<?php


namespace App\Http\Composer\Helper;


use App\Helpers\Core\Traits\InstanceCreator;

class InventoryPermissions
{
    use InstanceCreator;

    public function permissions(): array
    {
        return [
            [
                'name' => __t('stock'),
                'url' => route('support.inventory.stock',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_stock', 'create_stock', 'import_stock', 'view_stock_overview'
                ]),
            ],
            [
                'name' => __t('manage_lot'),
                'url' => route('support.manage.lot',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_lot', 'create_lot', 'lot_manage'
                ]),
            ],
            [
                'name' => __t('stock_adjustment'),
                'url' => route('support.stock.adjustment',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_stock_adjustments', 'create_stock_adjustments', 'update_stock_adjustments', 'delete_stock_adjustments'
                ]),
            ],
            [
                'name' => __t('import_stock'),
                'url' => route('support.import.stock',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'import_stock'
                ]),
            ],
            [
                'name' => __t('print_barcode'),
                'url' => route('support.print.barcode',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'generate_barcode_inventory'
                ]),
            ],
            [
                'name' => __t('print_qrcode'),
                'url' => route('support.print.qrcode',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'generate_qrcode'
                ]),
            ],
            [
                'name' => __t('internal_transfer'),
                'url' => route('support.internal.transfer',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any([
                    'view_internal_transfers', 'create_internal_transfers', 'update_internal_transfers',
                    'delete_internal_transfers'
                ]),
            ],
        ];
    }

    public function canVisit(): bool
    {
        return authorize_any([
            'view_lot', 'create_lot', 'lot_manage', 'view_stock', 'import_stock',
            'view_stock_adjustments', 'create_stock_adjustments', 'update_stock_adjustments',
            'delete_stock_adjustments', 'generate_barcode_inventory', 'generate_qrcode',
            'view_internal_transfers', 'create_internal_transfers', 'update_internal_transfers',
            'delete_internal_transfers'
        ]);
    }
}
