<?php

namespace App\Http\Controllers\Tenant\Sales;

use App\Filters\Tenant\VariantFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Sales\CashRegisterRequest;
use App\Models\Core\Setting\Setting;
use App\Models\Tenant\Discount\Discount;
use App\Models\Tenant\PaymentMethod\PaymentMethod;
use App\Models\Tenant\Sales\Cash\CashRegister;
use App\Models\Tenant\Sales\Cash\CashRegisterLog;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Pos\Inventory\Stock\StockService;
use App\Services\Tenant\Sales\SalesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleServiceController extends Controller
{
    public $variantFilter;
    public $saleService;
    public $stockService;

    public function __construct(SalesService $saleService, VariantFilter $variantFilter, StockService $stockService)
    {
        $this->variantFilter = $variantFilter;
        $this->saleService = $saleService;
        $this->stockService = $stockService;
    }

    public function productList()
    {
        return $this->saleService
            ->productVariantList($this->variantFilter);
    }

    public function paymentMethods()
    {
        return PaymentMethod::query()
            ->where('status_id', resolve(StatusRepository::class)->payment_methodActive())
            ->get();
    }

    public function getFlatDiscount(Request $request)
    {
        //get only flat discount for all off the product
        $request->validate([
            'branch_or_warehouse_id' => 'required'
        ]);
        $discount = Discount::query()
            ->where([
                'branch_or_warehouse_id' => $request->branch_or_warehouse_id,
                'discount_type' => 'flat_discount',
                'status_id' => resolve(StatusRepository::class)->discountActive()
            ])
            ->select('id', 'name', 'branch_or_warehouse_id', 'discount_type', 'type', 'value', 'start_at', 'end_at', 'status_id', 'note')
            ->orderBy('end_at', 'ASC')
            ->first();

        return [
            'discount_on_sub_total' => $discount ?? null
        ];
    }

    public function invoiceBasicInfo()
    {
        return Setting::where('name', 'sales_invoice_auto_generate')->first();
    }

    public function cashCounterOpenCloseStatus() {
        $cash_register = $this->saleService
            ->cashCounterOpenCloseStatus();
        return response()->json(['cash_register' => $cash_register]);
    }

    public function cashCounterOpenClose(CashRegisterRequest $request)
    {
        $this->saleService
            ->setAttributes($request->all())
            ->cashCounterOpenClose();
        return updated_responses('cash_counter');
    }

    public function cashRegisterInformation(CashRegister $cashRegister)
    {
        $cashRegisterLogs = CashRegisterLog::query()
            ->with('paymentMethod:id,name,alias')
            ->where('cash_register_id', $cashRegister->id)
            ->where('order_id', '!=', null)
            ->where('opening_time', '<=', $cashRegister->opening_time)
            ->where('closing_time', null)
            ->select('id', 'opening_balance', 'closing_balance', 'payment_method_id',
                'opening_time', 'closing_time', 'order_id', 'cash_register_id',
                DB::raw("(sum(cash_sales)) as total_sale_amount"))
            ->groupBy('payment_method_id', 'id', 'opening_balance', 'closing_balance',
                'opening_time', 'closing_time', 'order_id', 'cash_register_id')
            ->get();

        return [
            'cashRegister' => $cashRegister->load('status:id,name,class'),
            'cashRegisterLogs' => $cashRegisterLogs,
        ];
    }
}
