<?php

namespace App\Services\Pos\Inventory\Stock;

use App\Models\Pos\Inventory\Stock\Stock;
use App\Services\Core\BaseService;

class StockService extends BaseService
{
    public function __construct(Stock $stock)
    {
        $this->model = $stock;
    }

    public function checkVariantExistInStock($variant_id, $branch_or_warehouse_id = null): bool
    {
        return $this->model->query()
            ->where([
                'variant_id' => $variant_id,
                'branch_or_warehouse_id' => $branch_or_warehouse_id
            ])
            ->exists();
    }

    public function createStockAfterReceivingNewLot($lotVariant = [])
    {
        return $this->model->query()->create($lotVariant);
    }


    public function updateStockAfterNewLotReceived($lotVariant = []): StockService
    {
        $stock = $this->getStockByVariantId($lotVariant['variant_id'], $lotVariant['branch_or_warehouse_id']);

        $stock->update([
            'available_qty' => $lotVariant['available_qty'],
            'total_purchase_qty' => $lotVariant['total_purchase_qty'],
            'avg_purchase_price' => $lotVariant['avg_purchase_price']
        ]);

        return $this;
    }

    public function getStockByVariantId($variant_id, $branch_or_warehouse_id)
    {
        return $this->model->query()
            ->where([
                'variant_id' => $variant_id,
                'branch_or_warehouse_id' => $branch_or_warehouse_id
            ])
            ->first();
    }


    public function avgPurchasePriceForNewLot($availableQty = 0, $avgPurchasePrice = 0, $purchaseQty = 0, $finalUnitPurchasePrice = 0): float|int
    {
        return (($availableQty * $avgPurchasePrice) +
                ($purchaseQty * $finalUnitPurchasePrice)) / ($availableQty + $purchaseQty);
    }

    public function distributedAmountAccordingToTotalPayment($totalPayment, $eachPayment, $amountForDistribution): float|int
    {
        return (($amountForDistribution * $eachPayment) / $totalPayment);
    }

    public function updateStockAfterNewStockAdjustmentCreated($branch_or_warehouse_id = null, $variant_id = null, $total_adjustment_qty = null, $available_qty = null): StockService
    {
        $stock = $this->getStockByVariantId($variant_id, $branch_or_warehouse_id);
        $stock->update([
            'available_qty' => $available_qty,
            'total_adjustment_qty' => $total_adjustment_qty,
        ]);
        $stock->save();

        return $this;
    }

    public function updateOrCreateStockAfterCompleteInternalTransfer($internalTransferVariant = []): static
    {
        $stock_for_sender = $this->getStockByVariantId($internalTransferVariant['variant_id'], $internalTransferVariant['branch_or_warehouse_from_id']);
        $stock_for_receiver = $this->getStockByVariantId($internalTransferVariant['variant_id'], $internalTransferVariant['branch_or_warehouse_to_id']);

        //==========================
        //update sender stock
        //==========================
        $stock_for_sender->update([
            'available_qty' => $internalTransferVariant['available_qty_for_sender'],
            'total_internal_transfer_sent_qty' => $internalTransferVariant['total_internal_transfer_sent_qty']
        ]);
        $stock_for_sender->save();

        //==========================
        //update receiver stock
        //==========================
        if ($stock_for_receiver){
            $stock_for_receiver->update([
                'available_qty' => $internalTransferVariant['available_qty_for_receiver'],
                'total_internal_transfer_received_qty' => $internalTransferVariant['total_internal_transfer_received_qty']
            ]);
            $stock_for_receiver->save();
        }else{
            $this->createStockAfterCompleteInternalTransfer($internalTransferVariant);
        }

        return $this;
    }

    //create new stock variant for internal transfer receiver
    public function createStockAfterCompleteInternalTransfer($internalTransferVariant = []): static
    {
        $this->model->query()->create([
            'branch_or_warehouse_id' => $internalTransferVariant['branch_or_warehouse_to_id'],
            'variant_id' => $internalTransferVariant['variant_id'],
            'total_internal_transfer_received_qty' => $internalTransferVariant['total_internal_transfer_received_qty'],
            'available_qty' => $internalTransferVariant['available_qty_for_receiver'],
        ]);

        return $this;
    }


}