<?php

namespace App\Http\Controllers\Pos\Api\Barcode;

use App\Http\Controllers\Controller;
use App\Models\Pos\Product\Product\Variant;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{

    //generate upc / Barcode for product variant
    public function generateBarcodeNumber(): int
    {
        $number = mt_rand(1000000000, 9999999999);

        // call the same function if the barcode exists already
        if ($this->barcodeNumberExists($number)) {
            return $this->generateBarcodeNumber();
        }
        return $number;
    }

    public function barcodeNumberExists($number)
    {
        return Variant::query()->whereUpc($number)->exists();
    }

    public function UniqueUpcCheck($upc, $variant_id = null): string
    {
        if ($variant_id) {
            $upc_exist = Variant::query()
                ->where([['id', '!=', $variant_id], ['upc', $upc]])
                ->exists();
        } else {
            $upc_exist = $this->barcodeNumberExists($upc);
        }
        return $upc_exist ? 'not-unique' : 'unique';
    }

    private function prepareBarCode($upc, $type)
    {
        return $upc ? '<img src="data:image/png;base64,' . (new \Milon\Barcode\DNS1D)->getBarcodePNG((string)$upc, $type, 1.5,33) . '" alt="barcode"   />' : '';
    }

    private function prepareQRCode($upc, $type): string
    {
        return $upc ? '<img src="data:image/png;base64,' . (new \Milon\Barcode\DNS2D)->getBarcodePNG((string)$upc, $type) . '" alt="barcode"   />' : '';
    }

    public function generateBarCodeForInventory(Request $request): string
    {
       return $request->upc ? $this->prepareBarCode($request->upc ?? null, $request->type ?? 'C93') : '';
    }

    public function generateQRCodeForInventory(Request $request): string
    {
        return $request->upc ? $this->prepareQRCode($request->upc ?? null, $request->type ?? 'QRCODE') : '';
    }

    public function getVariantByBarcodeOrQrCode($reference_no)
    {
        return Variant::query()
            ->where('upc', $reference_no)
            ->select('id', 'name', 'product_id', 'upc', 'selling_price', 'status_id')
            ->with([
                'stock:id,branch_or_warehouse_id,variant_id,avg_purchase_price,total_purchase_qty,total_sales_qty,total_adjustment_qty,total_internal_transfer_sent_qty,total_internal_transfer_received_qty,available_qty'
            ])
            ->first();
    }
}
