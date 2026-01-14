<?php

namespace App\Http\Controllers\Tenant\Report;

use App\Exports\CashCounterExport;
use App\Filters\Tenant\CashRegisterLogFilter;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Sales\Cash\CashRegisterLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CashCounterReportController extends Controller
{
    public $filter;

    public function __construct(CashRegisterLogFilter $filter)
    {
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->cashCounterQuery()->paginate(request('per_page', 10));
    }

    public function cashCounterQuery()
    {
        return CashRegisterLog::query()
            ->select('id', 'order_id', 'payment_method_id', 'user_id', 'cash_register_id', 'branch_or_warehouse_id',
                'opening_balance', 'closing_balance', 'cash_receives', 'cash_sales', 'difference', 'opening_time',
                'closing_time', 'opened_by', 'closed_by', 'note', 'status_id')
            ->filters($this->filter)
            ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {
                $builder->where('branch_or_warehouse_id', request('branch_or_warehouse_id'));
            })
            ->latest()
//            ->where('order_id', '!=', null)
            ->with([
                'cashRegister:id,name',
                'branchOrWarehouse:id,name,type',
                'soldBy:id,first_name,last_name',
                'openedBy:id,first_name,last_name',
                'closedBy:id,first_name,last_name',
                'paymentMethod:id,name,alias',
                'order.customer:id,first_name,last_name',
                'status:id,name,class',
            ]);
    }

    public function exportCashCounterReport(Request $request)
    {
        $response =  Excel::download(new CashCounterExport(), 'cash_counter_report.xlsx',\Maatwebsite\Excel\Excel::XLSX);
        if (ob_get_contents()) ob_end_clean();
        return $response;
    }
}
