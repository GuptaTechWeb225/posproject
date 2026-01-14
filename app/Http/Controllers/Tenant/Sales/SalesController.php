<?php

namespace App\Http\Controllers\Tenant\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Sales\SalesRequest;
use App\Services\Tenant\Order\OrderService;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function sales(SalesRequest $request)
    {
        DB::transaction(function () use ($request) {
            $this->orderService
                ->generateInvoiceId()
                ->storeOrder()
                ->storeOrderProduct()
                ->makeTransactions();
        });

        return created_responses('sales');
    }
}
