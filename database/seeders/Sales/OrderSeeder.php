<?php

namespace Database\Seeders\Sales;

use App\Models\Pos\Product\Product\Product;
use App\Models\Tenant\Customer\Customer;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Order\OrderProduct;
use App\Models\Tenant\Sales\Cash\CashRegister;
use App\Models\Tenant\Sales\Cash\CashRegisterLog;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\Order\OrderService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function run()
    {
        $products = Product::with('variants')
            ->whereHas('variants.stock')->get();
        $customers = Customer::take($products->count())->get();
        foreach ($customers as $key => $customer) {
            if (isset($products[$key]) && $products[$key]->variants[0]->stock) {

                if ($products[$key]->variants[0]->stock->branch_or_warehouse_id == 1){ //1 in branch and 2 is warehouse
                //order save
                $invoiceId = $key + 1;
                $lastInvoiceId = $invoiceId;
                $order = new Order;
                $order->invoice_number = "POS-" . $invoiceId . "-sales";
                $order->cash_register_id = 1;
                $order->last_invoice_number = $lastInvoiceId;
                $order->customer_id = $customer->id;
                $order->branch_or_warehouse_id = $products[$key]->variants[0]->stock->branch_or_warehouse_id;


                $order->created_by = 1;
                $order->sub_total = $products[$key]->variants[0]->selling_price;
                $order->grand_total = $products[$key]->variants[0]->selling_price;
                $order->total_tax = 0;
                $order->discount = 0;
                $order->ordered_at = Carbon::now();
                $order->tenant_id = 1;
                if ($key % 2 == 0) {
                    $status = resolve(StatusRepository::class)->orderHold();
                } else {
                    $status = resolve(StatusRepository::class)->orderPending();
                }
                $order->status_id = $status;
                $order->save();
                //order products save
                $orderProduct = new OrderProduct;
                $orderProduct->order_id = $order->id;
                $orderProduct->branch_or_warehouse_id = $order->branch_or_warehouse_id;
                $orderProduct->variant_id = $products[$key]->variants[0]->id;
                $orderProduct->stock_id = $products[$key]->variants[0]->stock->id;
                $orderProduct->order_product_id = $products[$key]->variants[0]->product_id;
                $orderProduct->price = $products[$key]->variants[0]->selling_price;
                $orderProduct->avg_purchase_price = $products[$key]->variants[0]->selling_price;
                $orderProduct->selling_price = $products[$key]->variants[0]->selling_price;
                $orderProduct->quantity = 1;
                $orderProduct->tax_amount = 0;
                $orderProduct->tenant_id = 1;
                $orderProduct->ordered_at = Carbon::now();
                if ($key % 2 == 0) {
                    $discount = ($products[$key]->variants[0]->selling_price * 5) / 100;
                    $orderProduct->discount_type = 'percentage';
                    $orderProduct->discount_value = 5;
                    $orderProduct->discount_amount = $discount;
                    $orderProduct->sub_total = $products[$key]->variants[0]->selling_price - $discount;
                } else {
                    $orderProduct->discount_type = 'flat';
                    $orderProduct->discount_value = 10;
                    $orderProduct->discount_amount = 10;
                    $orderProduct->sub_total = $products[$key]->variants[0]->selling_price - 10;
                }
                $orderProduct->save();

                $cashCounter = CashRegister::find($order->cash_register_id);
                $userId = $order->created_by;
                $cashCounterLog = new CashRegisterLog;
                $cashCounterLog->user_id = $userId;
                $cashCounterLog->opened_by = $userId;
                $cashCounterLog->closed_by = $userId;
                $cashCounterLog->status_id = $cashCounter->status_id;
                $cashCounterLog->cash_register_id = $cashCounter->id;
                $cashCounterLog->branch_or_warehouse_id = $cashCounter->branch_or_warehouse_id;
                $cashCounterLog->opening_balance = $cashCounter->opening_balance;
                $cashCounterLog->closing_balance = $cashCounter->closing_balance;
                $cashCounterLog->cash_receives = 0;
                $cashCounterLog->cash_sales = 0;
                $cashCounterLog->difference = $cashCounter->opening_amount - 100;
                $cashCounterLog->opening_time = Carbon::now();
                $cashCounterLog->closing_time = Carbon::now();
                $cashCounterLog->note = null;
                $cashCounterLog->save();
            }
            }
        }
    }
}
