<?php


namespace App\Services\Pos\Inventory\Lot;

use App\Models\Pos\Inventory\Lot\Lot;
use App\Models\Pos\Inventory\LotVariant\LotVariant;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Pos\Inventory\Stock\StockService;
use App\Services\Tenant\TenantService;

class LotService extends TenantService
{
    public StockService $stockService;

    public function __construct(Lot $lot, StockService $stockService)
    {
        $this->model = $lot;
        $this->stockService = $stockService;
    }

    public function relations(): array
    {
        return [
            'lotVariants',
            'lotVariants.variant:id,name,upc,selling_price'
        ];
    }

    public function loadLotLists($filter)
    {
        return $this->model
            ->select('id', 'created_at', 'reference_no', 'supplier_id', 'status_id', 'branch_or_warehouse_id', 'purchase_date')
            ->branchOrWarehouse(request()->branch_or_warehouse_id)
            ->with(
                'branchOrWarehouse:id,name,type',
                'lotVariants',
                'supplier:id,name',
                'status:id,name,class,type',
            )
            ->withSum('lotVariants as total_unit', 'unit_quantity')
            ->filters($filter)
            ->latest('id')
            ->paginate(
                request('per_page', 15)
            );
    }

    public function storeLotData(): LotService
    {
        $this->model = $this->saveLotInfo();
        return $this;
    }

    public function storeLotVariantData(): LotService
    {
        $this->saveLotVariantInfo();
        return $this;
    }

    private function saveLotVariantInfo(): void
    {
        $lVariants = [];

        if ($this->getAttr('lotVariants')) {
            foreach ($this->getAttr('lotVariants') as $lotV) {
                $lVariants[] = $this->lotVariantRequest($lotV);
            }
        }

        $this->model->lotVariants()->insert($lVariants);
    }


    private function saveLotInfo()
    {

        return $this->model::query()
            ->create($this->lotRequest());
    }

    private function lotRequest(): array
    {
        return [
            'branch_or_warehouse_id' => $this->getAttr('branch_or_warehouse_id'),
            'supplier_id' => $this->getAttr('supplier_id'),
            'purchase_date' => $this->getAttr('purchase_date'),
            'status_id' => $this->getAttr('status_id'),
            'other_charge' => $this->getAttr('other_charge'),
            'discount_amount' => $this->getAttr('discount_amount'),
            'created_by' => $this->getAttr('created_by'),
            'reference_no' => $this->getAttr('reference_no'),
            'note' => $this->getAttr('note'),
            'total_amount' => $this->getAttr('total_amount'),
        ];
    }

    private function lotVariantRequest($lVariants = null): array
    {
        return [
            'lot_id' => $this->model->id,
            'variant_id' => $lVariants['variant_id'],
            'unit_quantity' => $lVariants['unit_quantity'],
            'unit_price' => $lVariants['unit_price'],
            'unit_tax_percentage' => $lVariants['unit_tax_percentage'],
            'total_unit_price' => $lVariants['total_unit_price'],
        ];
    }

    public function deleteLotVariant(): LotService
    {
        $this->model->lotVariants()->delete();
        return $this;
    }

    public function deleteLot(): LotService
    {
        $this->model->delete();
        return $this;
    }

    public function updateLot($options = []): LotService
    {
        $attributes = count($options) ? $options : request()->all();
        $this->model->fill($this->getFillAble($attributes))->save();
        return $this;
    }

    public function UpdateLotVariant(): LotService
    {
        if ($this->getAttr('lotVariants')) {
            foreach ($this->getAttr('lotVariants') as $variant) {
                if (isset($variant['id'])) {
                    //update existing variant
                    $variantObj = LotVariant::find($variant['id']);
                    $variantObj->update($variant);
                } else {
                    //crate new lot variant
                    $data['lot_id'] = $this->model->id;
                    $data['variant_id'] = $variant['variant_id'] ?? null;
                    $data['unit_quantity'] = $variant['unit_quantity'] ?? null;
                    $data['unit_price'] = $variant['unit_price'] ?? null;
                    $data['unit_tax_percentage'] = $variant['unit_tax_percentage'] ?? null;
                    $data['total_unit_price'] = $variant['total_unit_price'] ?? null;
                    LotVariant::query()->create($data);
                }
            }
        }

        return $this;
    }

    public function updateLotStatus(): LotService
    {
        $status_id = $this->getAttr('status_id');
        //only change lot status
        $this->model->update([
            'status_id' => $status_id
        ]);
        return $this;
    }


    //used from multiple method..
    public function updateStock($status_delivered_id, $adjusted_with_stock = false): LotService
    {
        $status_id = $this->getAttr('status_id');

        if (($this->model->adjusted_with_stock == $adjusted_with_stock and $status_delivered_id == (int)$status_id)) {

            //=======================================================================
            //Just fot getting total cost for whole lot
            //=======================================================================
            $totalPricesWithUnitTax = 0;
            foreach ($this->model->lotVariants as $s_variant) {
                $unit_price = $s_variant['unit_price'];

                //for tax percentage
                $unit_tax_amount = $this->stockService->distributedAmountAccordingToTotalPayment(
                    100, $s_variant['unit_tax_percentage'], $s_variant['unit_price']
                );

                $totalPricesWithUnitTax += $s_variant['unit_quantity'] * ($unit_price + $unit_tax_amount);
            }
            //-----------------------------------------------------------------------

            $variant = [];
            foreach ($this->model->lotVariants as $l_variant) {

                $variant['variant_id'] = $l_variant->variant_id;
                $variant['branch_or_warehouse_id'] = $this->model->branch_or_warehouse_id;

                $variantRowExistInStock = $this->stockService
                    ->checkVariantExistInStock($variant['variant_id'], $this->model->branch_or_warehouse_id);

                $oldStock = $this->stockService
                    ->getStockByVariantId($variant['variant_id'], $variant['branch_or_warehouse_id']);

                $variant['available_qty'] = $variantRowExistInStock
                    ? $l_variant['unit_quantity'] + $oldStock->available_qty
                    : $l_variant['unit_quantity'];

                $variant['total_purchase_qty'] = $variantRowExistInStock
                    ? $l_variant->unit_quantity + $oldStock->total_purchase_qty
                    : $l_variant->unit_quantity;


                //For calculating unit price with unit tax, discount and other charge
                $unit_price = $l_variant['unit_price'];

                $unit_tax_amount = $this->stockService->distributedAmountAccordingToTotalPayment(
                    100, $l_variant['unit_tax_percentage'], $l_variant['unit_price']
                );

                $unit_price_with_u_tax = $unit_price + $unit_tax_amount;


                // Distribute other cost to unit price according to percentage of total payment with unit tax
                $other_cost_for_each_qty = $this->stockService->distributedAmountAccordingToTotalPayment(
                    $totalPricesWithUnitTax,
                    $unit_price_with_u_tax,
                    $this->model->other_charge ?? 0
                );

                // Distribute discount to unit price according to percentage of total payment with unit tax
                $discount_for_each_qty = $this->stockService->distributedAmountAccordingToTotalPayment(
                    $totalPricesWithUnitTax,
                    $unit_price_with_u_tax,
                    $this->model->discount_amount ?? 0
                );

                $final_unit_price = ($unit_price_with_u_tax + $other_cost_for_each_qty) - $discount_for_each_qty;

                //new avg_purchase_price
                $variant['avg_purchase_price'] = $this->stockService->avgPurchasePriceForNewLot(
                    $variantRowExistInStock ? $oldStock->available_qty : 0,
                    $variantRowExistInStock ? $oldStock->avg_purchase_price : 0,
                    $l_variant['unit_quantity'],
                    $final_unit_price
                );

                $variantRowExistInStock
                    ? $this->stockService->updateStockAfterNewLotReceived($variant)
                    : $this->stockService->createStockAfterReceivingNewLot($variant);
            }

            //update lot table for adjusting stocks
            $this->model->update([
                'adjusted_with_stock' => 1
            ]);
        }

        return $this;
    }


    public function updateStockForAddingNewLotAsDelivered(): static
    {
        $status_id = $this->getAttr('status_id');
        $status_delivered_id = resolve(StatusRepository::class)->purchaseDelivered();

        if ($status_id === (int)$status_delivered_id) {
            $this->updateStock($status_delivered_id, null);
        }

        return $this;
    }


    //used for lot import
    public function deleteLotTempData($lotInfo)
    {
        if (count($lotInfo->lotVariants) < 1) {
            $lotInfo->delete();
        }

        return $this;
    }

}