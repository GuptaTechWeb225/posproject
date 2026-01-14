<?php

namespace App\Http\Resources\Sales;

use App\Models\Tenant\Order\ReturnOrderProduct;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReturnOrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return $this->collection->map(function ($data) {
            $returnOrderProductQuantity = ReturnOrderProduct::query()->where([
                'order_id' => $data->order_id,
                'variant_id' => $data->variant_id,
                'order_product_id' => $data->order_product_id,
            ])->get();
            return [
                'id' => $data->id,
                'order_id' => $data->order_id,
                'stock_id' => $data->stock_id,
                'variant_id' => $data->variant_id,
                'order_product_id' => $data->order_product_id,
                'price' => $data->price,
                'quantity' => $data->quantity - $returnOrderProductQuantity->sum('quantity'),
                'tax_amount' => $data->tax_amount - $returnOrderProductQuantity->sum('tax_amount'),
                'discount_type' => $data->discount_type,
                'discount_value' => $data->discount_value,
                'discount_amount' => $data->discount_amount - $returnOrderProductQuantity->sum('discount_amount'),
                'sub_total' => $data->sub_total - $returnOrderProductQuantity->sum('sub_total'),
                'variant' => [
                    'id' => $data->variant->id ?? null,
                    'product_id' => $data->variant->product_id ?? null,
                    'name' => $data->variant->name ?? null,
                    'tax_id' => $data->variant->tax_id ?? null
                ],
                'unit' => [
                    'id' => $data->variant->product->unit->id ?? null,
                    'name' => $data->variant->product->unit->name ?? null,
                    'short_name' => $data->variant->product->unit->short_name ?? null
                ],
            ];
        });
    }
}
