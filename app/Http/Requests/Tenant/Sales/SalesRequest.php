<?php


namespace App\Http\Requests\Tenant\Sales;


use App\Http\Requests\Tenant\TenantRequest;

class SalesRequest extends TenantRequest
{
    public function rules(): array
    {
        return [
            //Order
            'branch_id' => 'required|exists:branches,id',
            'status_id' => 'required|exists:statuses,id',
            'created_by' => 'required|exists:users,id',
            'type' => 'required|string',
            'sub_total' => 'required',
            'total_tax' => 'required',
            'discount' => 'required',
            'note' => 'required|string',
            'ordered_at' => 'required|date|date_format:Y-m-d H:i:s',

            //Order Product
            'order_products' => 'required|array',
            'order_products.*.stock_id' => 'required|exists:stocks,id',
            'order_products.*.order_product_id' => 'nullable',
            'order_products.*.price' => 'required',
            'order_products.*.quantity' => 'required',
            'order_products.*.tax_type' => 'required',
            'order_products.*.tax_amount' => 'required',
            'order_products.*.discount' => 'required',
            'order_products.*.sub_total' => 'required',
            'order_products.*.note' => 'required|string',
            'order_products.*.tenant_id' => 'required',

            //Transactions
            'transactions' => 'required|array',
            'transactions.*.payment_method_id' => 'required|exists:payment_methods,id',
            'transactions.*.created_by' => 'required|exists:users,id',
            'transactions.*.transaction_at' => 'required|date|date_format:Y-m-d H:i:s',
        ];
    }
}