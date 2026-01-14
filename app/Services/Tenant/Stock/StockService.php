<?php


namespace App\Services\Tenant\Stock;


use App\Models\Pos\Inventory\Stock\Stock;
use App\Services\Tenant\TenantService;

class StockService extends TenantService
{
    public function __construct(Stock $stock)
    {
        $this->model = $stock;
    }

    public function storeStock()
    {
        $this->model
            ->fill($this->getFillAble(request()->only(
                'lot_id', 'variant_id', 'purchase_price', 'expire_date', 'manufacturing_date', 'bar_code', 'sku', 'tax_id', 'tax_type', 'created_by', 'tenant_id',
            )))
            ->save();

        return $this;
    }

    public function storeStockQuantities()
    {
        $storeStockQuantities = [];

        if ($this->getAttr('stock_quantities')) {
            foreach ($this->getAttr('stock_quantities') as $storeStockQuantity) {

                $orderProducts[] = $this->storeStockQuantityRequest($storeStockQuantity);
            }
        }

        $this->model
            ->orderProducts()
            ->insert($storeStockQuantities);

        return $this;
    }

    public function storeStockQuantityRequest($storeStockQuantity)
    {
        return [
            'stock_id' => $this->model->id,
            'adjustment_type_id' => $storeStockQuantity['adjustment_type_id'],
            'quantity' => $storeStockQuantity['quantity'],
        ];
    }
}