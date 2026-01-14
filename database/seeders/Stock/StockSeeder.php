<?php

namespace Database\Seeders\Stock;

use App\Models\Pos\Inventory\Lot\Lot;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Pos\Inventory\Stock\StockService;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function run()
    {
        $lotDeliveredStatusId = resolve(StatusRepository::class)->purchaseDelivered();

        //01. Update lot status to delivered for updating stock
        //------------------------------------------------------
        Lot::query()->orderByDesc('id')
            //->take(3)
            ->update([
                'status_id' => $lotDeliveredStatusId,
                'adjusted_with_stock' => true
            ]);

        //02. Query / Get all lots with status delivered
        //------------------------------------------------------

        $deliveredLots = Lot::query()
            ->where('status_id', $lotDeliveredStatusId)
            ->select(
                'id', 'branch_or_warehouse_id', 'status_id', 'other_charge', 'discount_amount', 'adjusted_with_stock'
            )
            ->get();

        //03.update stock for each lot with variants
        //------------------------------------------------------

        foreach ($deliveredLots as $lot) {
            $this->calculationAndUpdateStock($lot);
        }
    }


    public function calculationAndUpdateStock($lot)
    {
        //=======================================================================
        //Just fot getting total cost for a single lot with multiple variants
        //=======================================================================
        $totalPricesWithUnitTax = 0;
        foreach ($lot->lotVariants as $s_variant) {
            $unit_price = $s_variant['unit_price'];
            //$unit_tax_amount = $s_variant['unit_tax_percentage'] ?? 0; //for flat  tax

            //for tax percentage
            $unit_tax_amount = $this->stockService->distributedAmountAccordingToTotalPayment(
                100, $s_variant['unit_tax_percentage'] ?? 0, $s_variant['unit_price']
            );

            $totalPricesWithUnitTax += $s_variant['unit_quantity'] * ($unit_price + $unit_tax_amount);
        }
        //-----------------------------------------------------------------------

        $variant = [];
        foreach ($lot->lotVariants as $l_variant) {
            $variant['variant_id'] = $l_variant->variant_id;
            $variant['branch_or_warehouse_id'] = $lot->branch_or_warehouse_id;

            $variantRowExistInStock = $this->stockService
                ->checkVariantExistInStock($l_variant->variant_id, $lot->branch_or_warehouse_id);

            $oldStock = $this->stockService
                ->getStockByVariantId($l_variant->variant_id, $lot->branch_or_warehouse_id);

            $variant['available_qty'] = $variantRowExistInStock
                ? $l_variant['unit_quantity'] + $oldStock->available_qty
                : $l_variant['unit_quantity'];

            $variant['total_purchase_qty'] = $variantRowExistInStock
                ? $l_variant->unit_quantity + $oldStock->total_purchase_qty
                : $l_variant->unit_quantity;


            //For calculating unit price with unit tax, discount and other charge
            $unit_price = $l_variant['unit_price'];

            //tax percentage
            $unit_tax_amount = $this->stockService->distributedAmountAccordingToTotalPayment(
                100, $l_variant['unit_tax_percentage'] ?? 0, $l_variant['unit_price']
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

    }
}
