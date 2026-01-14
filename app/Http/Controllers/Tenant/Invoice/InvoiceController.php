<?php

namespace App\Http\Controllers\Tenant\Invoice;

use App\Filters\Tenant\OrderFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Sales\ReturnOrderCollection;
use App\Models\Tenant\Order\Order;

class InvoiceController extends Controller
{
    public $model;
    public $filter;

    public function __construct(Order $order, OrderFilter $orderFilter)
    {
        $this->model = $order;
        $this->filter = $orderFilter;
    }

    public function index()
    {
        return $this->model->query()
            ->filters($this->filter)
            ->branchOrWarehouse(request()->branch_or_warehouse_id)
            ->with([
                'branchOrWarehouse:id,name,type',
                'createdBy:id,first_name,last_name',
                'customer:id,first_name,last_name',
                'status',
                'transactions:id,payment_method_id,transactionable_id',
                'transactions.paymentMethod:id,name,alias',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(request('per_page', 15));
    }

    public function invoiceView(Order $invoice)
    {
        return $invoice->load(
            'orderProducts',
            'orderProducts.variant:id,name,product_id,upc',
            'orderProducts.variant.product.unit:id,name',
            'orderProducts.variant.stock',
            'createdBy:id,first_name,last_name',
            'status',
            'customer:id,first_name,last_name',
            'customer.phoneNumbers',
            'branchOrWarehouse:id,name,location',
            'paymentMethod',
            'tax'
        );
    }

    public function returnInvoiceView(Order $order)
    {
        $orderData = $order;
        $orderProductList = new ReturnOrderCollection($orderData->orderProducts);

        return [
            'order' => $order,
            'orderProductList' => $orderProductList
        ];

    }
}
