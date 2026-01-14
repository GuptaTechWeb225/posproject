<?php

namespace Database\Seeders\App\Traits;

trait SalesInvoicePermissionTrait
{
    public function sales_invoice($type, $group = null): array
    {
        return [
            [
                'name' => 'view_invoice',
                'type_id' => $type,
                'group_name' => $group ?? 'sales_invoice'
            ],
            [
                'name' => 'list_invoice',
                'type_id' => $type,
                'group_name' => $group ?? 'sales_invoice'
            ],
            [
                'name' => 'sales_invoice_generate',
                'type_id' => $type,
                'group_name' => $group ?? 'sales_invoice'
            ],
        ];
    }
}
