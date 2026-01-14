<?php

namespace App\Http\Controllers\Tenant\Sales\SaleReturn;

use App\Filters\Tenant\SalesReturnFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Sales\SaleReturnRequest;
use App\Models\Tenant\Order\ReturnOrder;
use App\Services\Tenant\Order\OrderService;
use App\Services\Tenant\SalesReturn\SalesReturnService;
use PDF;

class SaleReturnController extends Controller
{
    public OrderService $orderService;

    public function __construct(SalesReturnService $returnService, SalesReturnFilter $salesReturnFilter, OrderService $orderService)
    {
        $this->service = $returnService;
        $this->filter = $salesReturnFilter;
        $this->orderService = $orderService;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->branchOrWarehouse(request()->branch_or_warehouse_id)
            ->with([
                'branchOrWarehouse:id,name,type',
                'createdBy:id,first_name,last_name',
                'customer:id,first_name,last_name',
                'order:id,ordered_at',
            ])
            ->paginate(request('per_page', 15));
    }

    public function maxMinPrice(): array
    {
        return $this->service->maxMinPriceAmount();
    }

    public function store(SaleReturnRequest $request)
    {
        return $this->service
            ->setAttributes($request->all())
            ->generateInvoiceId()
            ->store()
            ->storeReturnOrderProduct()
            ->makeTransactions()
            ->generateReturnInvoiceTemplate();
    }


    public function returnOrderView(ReturnOrder $returnOrder): ReturnOrder
    {
        return $returnOrder->load(
            'returnOrderProducts.variant.product.unit:id,name',
            'returnOrderProducts.variant.stock',
            'createdBy:id,first_name,last_name',
            'customer:id,first_name,last_name',
            'customer.phoneNumbers',
            'branchOrWarehouse:id,name,location',
            'returnOrderProducts.variant.product.unit'
        );
    }

    public function generateInvoice($id): array
    {
        $returnOrder = ReturnOrder::find($id);
        return $this->service->setModel($returnOrder)->generateReturnInvoiceTemplate();
    }
}
