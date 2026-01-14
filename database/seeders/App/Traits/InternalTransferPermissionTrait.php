<?php

namespace Database\Seeders\App\Traits;

trait InternalTransferPermissionTrait
{
    public function internalTransfer($type, $group = null)
    {
        return [
            [
                'name' => 'view_internal_transfers',
                'type_id' => $type,
                'group_name' => $group ?? 'Internal transfer'
            ],
            [
                'name' => 'create_internal_transfers',
                'type_id' => $type,
                'group_name' => $group ?? 'Internal transfer'
            ],
            [
                'name' => 'update_internal_transfers',
                'type_id' => $type,
                'group_name' => $group ?? 'Internal transfer'
            ],
            [
                'name' => 'delete_internal_transfers',
                'type_id' => $type,
                'group_name' => $group ?? 'Internal transfer'
            ],
            [
                'name' => 'change_internal_transfer_status',
                'type_id' => $type,
                'group_name' => $group ?? 'Internal transfer'
            ],
        ];
    }
}