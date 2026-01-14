<?php

namespace App\Services\Import;

use App\Models\Pos\Inventory\Lot\Lot;
use App\Models\Pos\Inventory\LotVariant\LotVariant;
use App\Models\Pos\Product\Product\Variant;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Core\BaseService;
use App\Services\Pos\Inventory\Lot\LotService;
use App\Services\Pos\Inventory\Stock\StockService;

class LotImportService extends BaseService
{
    protected LotService $lotService;
    protected StockService $stockService;

    public function __construct(LotService $lotService,StockService $stockService, Lot $lot)
    {
        $this->lotService = $lotService;
        $this->stockService = $stockService;

        $this->model = $lot;
    }

    //used from lot / stock import
    public function storeLotData(): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        $lotInfo = Lot::query()->create($this->lotInfoRequest());
        return $lotInfo;
    }

    private function lotInfoRequest(){
        return [
            "branch_or_warehouse_id" => $this->getAttr('branch_or_warehouse_id'),
            "supplier_id" => $this->getAttr('supplier_id'),
            "purchase_date" => $this->getAttr('purchase_date'),
            "status_id" => $this->getAttr('purchase_status'),
            "reference_no" => $this->getAttr('reference_no'),
        ];
    }

    public function storeLotVariantData(): static
    {
        LotVariant::query()->create($this->lotVariantRequest());
        return $this;
    }

    private function lotVariantRequest(){

        $lot_id = $this->getAttr('lot_id');
        $variant_id = Variant::query()->where('name', $this->getAttr('variant_name'))->first()->id ?? null;
        return [
            'variant_id' => $variant_id,
            'unit_quantity' =>  $this->getAttr('unit_quantity'),
            'unit_price' =>  $this->getAttr('unit_price'),
            'unit_tax_percentage' =>  $this->getAttr('unit_tax_percentage'),
            'total_unit_price' =>  $this->getAttr('unit_quantity') * $this->getAttr('unit_price'),
            'lot_id' => $lot_id,
        ];
    }

    public function updateStock($lot_id): static
    {
        $lotInfo = Lot::query()->find($lot_id);
        $this->model = $lotInfo;

        $status_id = $lotInfo->status_id;
        $status_delivered_id = resolve(StatusRepository::class)->purchaseDelivered();

        if ($status_id === $status_delivered_id){
            $this->lotService
                ->setModel($lotInfo)
                ->setAttrs(['status_id' => $lotInfo->status_id])
                ->updateStock($status_delivered_id)
                ->deleteLotTempData($lotInfo);
        }

        return $this;
    }

}