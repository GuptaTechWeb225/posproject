<?php

namespace App\Http\Controllers\Tenant\Report;

use App\Exports\UserReportExport;
use App\Filters\Core\UserFilter;
use App\Filters\Pos\Inventory\LotFilter;
use App\Filters\Tenant\OrderFilter;
use App\Filters\Tenant\SalesReturnFilter;
use App\Http\Controllers\Controller;
use App\Models\Core\Auth\User;
use App\Models\Pos\Inventory\Lot\Lot;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Order\ReturnOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserReportController extends Controller
{
    public $user;
    public $userFilter;
    public $order;
    public $purchase;
    public $orderFilter;
    public $purchaseFilter;
    public $returnOrder;
    public $returnFilter;

    public function __construct(
        User $user,
        UserFilter $userFilter,
        Order $order,
        Lot $lot,
        OrderFilter $orderFilter,
        LotFilter $lotFilter,
        ReturnOrder $returnOrder,
        SalesReturnFilter $returnFilter
    )
    {
        $this->user = $user;
        $this->userFilter = $userFilter;
        $this->order = $order;
        $this->purchase = $lot;
        $this->orderFilter = $orderFilter;
        $this->purchaseFilter = $lotFilter;
        $this->returnOrder = $returnOrder;
        $this->returnFilter = $returnFilter;
    }

    public function index()
    {
        return $this->user->query()
            ->filters($this->userFilter)
            ->withCount(['orders', 'lots', 'returnOrders', 'internalTransfers', 'adjustments'])
            ->paginate(
                request('per_page' ?? 10)
            );
    }

    public function salesReport(User $user)
    {
        return $this->order->query()
            ->filters($this->orderFilter)
            ->with([
                'createdBy:id,first_name,last_name',
                'customer:id,first_name,last_name',
                'branchOrWarehouse:id,name,type',
                'status:id,name,class',
                'cashRegister:id,name'
            ])
            ->where('created_by', $user->id)
            ->paginate(request('per_page' ?? 10));
    }

    public function salesReturnReport(User $user)
    {
        return $this->returnOrder->query()
            ->filters($this->returnFilter)
            ->with([
                'branchOrWarehouse:id,name,type',
                'createdBy:id,first_name,last_name',
                'customer:id,first_name,last_name',
            ])
            ->where('created_by', $user->id)
            ->paginate(request('per_page' ?? 10));
    }

    public function purchaseReport(User $user)
    {
        return $this->purchase->query()
            ->where('created_by', $user->id)
            ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {
                $builder->where('id', request('branch_or_warehouse_id'));
            })
            ->select('id', 'created_at', 'reference_no', 'supplier_id', 'status_id', 'branch_or_warehouse_id', 'discount_amount', 'created_by', 'other_charge', 'total_amount')
            ->with(
                'branchOrWarehouse:id,name,type',
                'lotVariants',
                'createdBy:id,first_name,last_name',
                'supplier:id,name',
                'status:id,name,class,type',
            )
            ->withSum('lotVariants as total_unit', 'unit_quantity')
            ->filters($this->purchaseFilter)
            ->latest('id')
            ->paginate(request('per_page', 10));
    }

    public function exportSalesReport(Request $request)
    {
        $response = Excel::download(new UserReportExport, 'user_report.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        if (ob_get_contents()) ob_end_clean();
        return $response;
    }
}
