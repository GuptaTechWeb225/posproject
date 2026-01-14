<?php

namespace Database\Seeders\App\Traits;

trait InvoiceDuePermissionTrait
{
    public function invoice_due($type, $group = null): array
    {
        return [
            [
                'name' => 'info_due',
                'type_id' => $type,
                'group_name' => $group ?? 'due_collection'
            ],
            [
                'name' => 'receive_due',
                'type_id' => $type,
                'group_name' => $group ?? 'due_collection'
            ],
        ];
    }
}