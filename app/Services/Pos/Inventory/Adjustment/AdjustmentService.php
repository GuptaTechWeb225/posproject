<?php

namespace App\Services\Pos\Inventory\Adjustment;

use App\Exceptions\GeneralException;
use App\Models\Pos\Inventory\Adjustment\Adjustment;
use App\Models\Pos\Inventory\AdjustmentVariant;
use App\Services\Core\BaseService;
use App\Services\Pos\Inventory\Stock\StockService;

class AdjustmentService extends BaseService
{
    protected StockService $stockService;

    public function __construct(Adjustment $adjustment, StockService $stockService)
    {
        $this->model = $adjustment;
        $this->stockService = $stockService;
    }

    public function relations(): array
    {
        return [
            'adjustmentVariants',
            'adjustmentVariants.variant:id,name,upc',
            'adjustmentVariants.variant.stock:id,branch_or_warehouse_id,available_qty,variant_id'
        ];
    }

    public function storeAdjustmentData(): AdjustmentService
    {
        $this->model = $this->saveAdjustmentInfo();
        return $this;
    }

    private function saveAdjustmentInfo()
    {
        return $this->model->query()
            ->create($this->AdjustmentRequest());
    }

    public function AdjustmentRequest(): array
    {
        return [
            'branch_or_warehouse_id' => $this->getAttr('branch_or_warehouse_id'),
            'adjustment_date' => $this->getAttr('adjustment_date'),
            'created_by' => $this->getAttr('created_by'),
            'reference_no' => $this->getAttr('reference_no'),
            'note' => $this->getAttr('note'),
        ];
    }

    private function adjustmentVariantRequest($adjVariants = null): array
    {
        return [
            'adjustment_id' => $this->model->id,
            'variant_id' => $adjVariants['variant_id'],
            'unit_quantity' => $adjVariants['unit_quantity'],
            'adjustment_type' => $adjVariants['adjustment_type'],
        ];
    }


    public function storeAdjustmentVariantData(): AdjustmentService
    {
        $this->saveAdjustmentVariantInfo();
        return $this;
    }

    private function saveAdjustmentVariantInfo()
    {
        $adjustmentVariants = [];

        if ($this->getAttr('adjustmentVariants')) {
            foreach ($this->getAttr('adjustmentVariants') as $adjVariant) {
                $adjustmentVariants[] = $this->adjustmentVariantRequest($adjVariant);
            }
        }

        $this->model->adjustmentVariants()->insert($adjustmentVariants);
    }

    public function updateStock(): AdjustmentService
    {
        foreach ($this->model->adjustmentVariants()->get() as $adjustmentVariant) {
            //stock adjustment can add if variant is added  in stock  (variant row exist in stock)
            $variantRowExistInStock = $this->stockService
                ->checkVariantExistInStock($adjustmentVariant->variant_id, $this->model->branch_or_warehouse_id);

            $oldStock = $this->stockService
                ->getStockByVariantId($adjustmentVariant->variant_id, $this->model->branch_or_warehouse_id);


            throw_if(!$variantRowExistInStock, new GeneralException(__t('stock_adjustment_only_possible_for_existing_products_in_stock')));

            $total_adjustment_qty = $adjustmentVariant->adjustment_type === 'addition'
                ? $oldStock->total_adjustment_qty + $adjustmentVariant->unit_quantity
                : $oldStock->total_adjustment_qty - $adjustmentVariant->unit_quantity;

            $available_qty = $adjustmentVariant->adjustment_type === 'addition'
                ? $oldStock->available_qty + $adjustmentVariant->unit_quantity
                : $oldStock->available_qty - $adjustmentVariant->unit_quantity;

            $this->stockService->updateStockAfterNewStockAdjustmentCreated($this->model->branch_or_warehouse_id,
                $adjustmentVariant->variant_id,
                $total_adjustment_qty, $available_qty
            );
        }

        return $this;
    }

    public function deleteAdjustmentFromStock(): AdjustmentService
    {
        foreach ($this->model->adjustmentVariants as $adjustmentVariant) {
            //stock adjustment will update if variant row exist in stock
            $variantRowExistInStock = $this->stockService
                ->checkVariantExistInStock($adjustmentVariant->variant_id, $this->model->branch_or_warehouse_id);

            $oldStock = $this->stockService
                ->getStockByVariantId($adjustmentVariant->variant_id, $this->model->branch_or_warehouse_id);

            throw_if(!$variantRowExistInStock, new GeneralException(__t('stock_adjustment_only_possible_for_existing_products_in_stock')));

            //reverse of add stock adjustment
            $total_adjustment_qty = $adjustmentVariant->adjustment_type === 'addition'
                ? $oldStock->total_adjustment_qty - $adjustmentVariant->unit_quantity
                : $oldStock->total_adjustment_qty + $adjustmentVariant->unit_quantity;

            //reverse of add stock adjustment
            $available_qty = $adjustmentVariant->adjustment_type === 'addition'
                ? $oldStock->available_qty - $adjustmentVariant->unit_quantity
                : $oldStock->available_qty + $adjustmentVariant->unit_quantity;

            $this->stockService
                ->updateStockAfterNewStockAdjustmentCreated(
                    $this->model->branch_or_warehouse_id, $adjustmentVariant->variant_id, $total_adjustment_qty, $available_qty
                );
        }

        return $this;
    }

    public function deleteAdjustmentVariant(): AdjustmentService
    {
        $this->model->adjustmentVariants()->delete();
        return $this;
    }

    public function deleteAdjustment(): AdjustmentService
    {
        $this->model->delete();
        return $this;
    }

    public function updateAdjustmentData($options = []): static
    {
        $attributes = count($options) ? $options : request()->all();

        $this->model
            ->fill($this->getFillAble($attributes))
            ->save();

        return $this;

    }

    public function updateAdjustmentVariantDataAndUpdateStock(): static
    {
        if ($this->getAttr('adjustmentVariants')) {
            foreach ($this->getAttr('adjustmentVariants') as $adjustment_variant) {
                if (isset($adjustment_variant['id'])) {
                    //update existing adjustment variant
                    $variantObj = AdjustmentVariant::find($adjustment_variant['id']);
                    $variantObj->update($adjustment_variant);

                    //update stock for existing adjustment variant..
                    $this->updateStockAfterChangingAdjustment($adjustment_variant);
                } else {
                    //crate adjustment variant
                    $data['adjustment_id'] = $this->model->id;
                    $data['variant_id'] = $adjustment_variant['variant_id'] ?? null;
                    $data['created_by'] = $adjustment_variant['created_by'] ?? null;
                    $data['unit_quantity'] = $adjustment_variant['unit_quantity'] ?? null;
                    $data['adjustment_type'] = $adjustment_variant['adjustment_type'] ?? null;
                    AdjustmentVariant::query()->create($data);

                    //update stock for new adjustment variant..
                    $this->updateStockForAddingNewAdjustmentVariant($data);
                }
            }
        }

        return $this;
    }


    public function updateStockAfterChangingAdjustment($adjustmentVariantToUpdate): static
    {
        $variantRowExistInStock = $this->stockService
            ->checkVariantExistInStock($adjustmentVariantToUpdate['variant_id'], $this->model->branch_or_warehouse_id);

        throw_if(!$variantRowExistInStock, new GeneralException(__t('stock_adjustment_only_possible_for_existing_products_in_stock')));

        $oldAdjustmentVariantData = AdjustmentVariant::find($adjustmentVariantToUpdate['id']);

        $oldStock = $this->stockService
            ->getStockByVariantId($adjustmentVariantToUpdate['variant_id'], $this->model->branch_or_warehouse_id);

        //------------------------------
        //change stock as initial
        //------------------------------
        $previous_stock_total_adjustment_qty_was = $oldAdjustmentVariantData->adjustment_type === 'addition'
            ? $oldStock->total_adjustment_qty - $oldAdjustmentVariantData->unit_quantity
            : $oldStock->total_adjustment_qty + $adjustmentVariantToUpdate['unit_quantity'];


        $previous_available_stock_qty_was = $oldAdjustmentVariantData->adjustment_type === 'addition'
            ? $oldStock->available_qty - $oldAdjustmentVariantData->unit_quantity
            : $oldStock->available_qty + $oldAdjustmentVariantData['unit_quantity'];

        //------------------------------
        //to update stock
        //------------------------------
        $total_adjustment_qty = $adjustmentVariantToUpdate['adjustment_type'] === 'addition'
            ? $previous_stock_total_adjustment_qty_was + $adjustmentVariantToUpdate['unit_quantity']
            : $previous_stock_total_adjustment_qty_was - $adjustmentVariantToUpdate['unit_quantity'];

        $available_qty = $adjustmentVariantToUpdate['adjustment_type'] === 'addition'
            ? $previous_available_stock_qty_was + $adjustmentVariantToUpdate['unit_quantity']
            : $previous_available_stock_qty_was - $adjustmentVariantToUpdate['unit_quantity'];

        $this->stockService
            ->updateStockAfterNewStockAdjustmentCreated(
                $this->model->branch_or_warehouse_id, $adjustmentVariantToUpdate['variant_id'], $total_adjustment_qty, $available_qty
            );

        return $this;
    }

    public function updateStockForAddingNewAdjustmentVariant($newAdjustmentVariant): static
    {
        //stock adjustment can add if variant is added  in stock  (variant row exist in stock)
        $variantRowExistInStock = $this->stockService
            ->checkVariantExistInStock($newAdjustmentVariant['variant_id'], $this->model->branch_or_warehouse_id);

        $oldStock = $this->stockService
            ->getStockByVariantId($newAdjustmentVariant['variant_id'], $this->model->branch_or_warehouse_id);

        throw_if(!$variantRowExistInStock, new GeneralException(__t('stock_adjustment_only_possible_for_existing_products_in_stock')));

        $total_adjustment_qty = $newAdjustmentVariant['adjustment_type'] === 'addition'
            ? $oldStock->total_adjustment_qty + $newAdjustmentVariant['unit_quantity']
            : $oldStock->total_adjustment_qty - $newAdjustmentVariant['unit_quantity'];

        $available_qty = $newAdjustmentVariant['adjustment_type'] === 'addition'
            ? $oldStock->available_qty + $newAdjustmentVariant['unit_quantity']
            : $oldStock->available_qty - $newAdjustmentVariant['unit_quantity'];

        $this->stockService
            ->updateStockAfterNewStockAdjustmentCreated(
                $this->model->branch_or_warehouse_id, $newAdjustmentVariant['variant_id'], $total_adjustment_qty, $available_qty
            );

        return $this;
    }


}