<?php

namespace Database\Seeders\App\Traits;

trait GenerateAndPrintBarOrQrCodePermissionTrait
{
    public function barcode_qrcode($type, $group = null): array
    {
        return [
            [
                'name' => 'generate_barcode_inventory',
                'type_id' => $type,
                'group_name' => $group ?? 'Barcode'
            ],
            [
                'name' => 'generate_qrcode',
                'type_id' => $type,
                'group_name' => $group ?? 'QR Code'
            ],
            [
                'name' => 'manage_barcode_generate',
                'type_id' => $type,
                'group_name' => $group ?? 'Barcode'
            ],
            [
                'name' => 'manage_qrcode_generate',
                'type_id' => $type,
                'group_name' => $group ?? 'Barcode'
            ],
        ];
    }
}