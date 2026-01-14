<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StockCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|\Illuminate\Contracts\Support\Arrayable
    {
        return $this->collection->map(function ($data) {
            return [
                'id' => $data->id ?? null,
                'variant_id' => $data->variant_id ?? null,
                'available_qty' => $data->available_qty ?? null,
                'updated_at' => $data->updated_at->diffForHumans() ?? null,
                'branch_or_warehouse_id' => $data->branch_or_warehouse_id ?? null,
                'variant' => $data->variant ? $data->variant->only(['id', 'name', 'upc','product_id']) : '',
                'thumbnail' => $data->variant->thumbnail ? $data->variant->thumbnail->makeHidden(['created_at', 'updated_at']) : '',
                'branchOrWarehouse' => $data->branchOrWarehouse ? $data->branchOrWarehouse->only(['id', 'name']) : ''
            ];
        });
    }
}