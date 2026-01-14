<?php

namespace Database\Seeders\Sales;

use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Order\ReturnOrder;
use App\Models\Tenant\Order\ReturnOrderProduct;
use App\Repositories\Core\Status\StatusRepository;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderReturnSeeder extends Seeder
{

    public function run()
    {
        $orders = Order::query()->get();
        foreach ($orders as $key => $order) {
            //return order save
            $returnInvoiceId = $key + 1;
            $returnOrder = new ReturnOrder;
            $returnOrder->order_id = $order->id;
            $returnOrder->branch_or_warehouse_id = $order->branch_or_warehouse_id;
            $returnOrder->customer_id = $order->customer_id;
            $returnOrder->invoice_number = "POS-" . $returnInvoiceId . "-return";
            $returnOrder->last_invoice_number = $returnInvoiceId;
            $returnOrder->order_invoice_number = $order->invoice_number;
            $returnOrder->reference_number = $key + 1;
            $returnOrder->created_by = $order->created_by;
            $returnOrder->sub_total = $order->sub_total;
            $returnOrder->total_tax = $order->total_tax;
            $returnOrder->discount = $order->discount;
            $returnOrder->status_id = resolve(StatusRepository::class)->orderReturn();
            $returnOrder->returned_at = Carbon::now();
            $returnOrder->save();

            //return order products
            $returnOrderProduct = new ReturnOrderProduct;
            $returnOrderProduct->order_id = $returnOrder->order->id;
            $returnOrderProduct->return_order_id = $returnOrder->id;
            $returnOrderProduct->variant_id = $order->orderProducts[0]->variant->id;
            $returnOrderProduct->stock_id = $order->orderProducts[0]->stock_id;
            $returnOrderProduct->order_product_id = $order->orderProducts[0]->order_product_id;
            $returnOrderProduct->price = $order->orderProducts[0]->price;
            $returnOrderProduct->quantity = $order->orderProducts[0]->quantity;
            $returnOrderProduct->tax_amount = $order->orderProducts[0]->tax_amount;
            $returnOrderProduct->discount_type = $order->orderProducts[0]->discount_type;
            $returnOrderProduct->discount_value = $order->orderProducts[0]->discount_value;
            $returnOrderProduct->discount_amount = $order->orderProducts[0]->discount_amount;
            $returnOrderProduct->sub_total = $order->orderProducts[0]->sub_total;
            $returnOrderProduct->tenant_id = $order->orderProducts[0]->tenant_id;
            $returnOrderProduct->save();

        }
    }

}
