<?php

namespace App\Services\Tenant\Report;

use App\Filters\Pos\Inventory\BranchOrWarehouseFilter;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Tenant\Order\Order;
use App\Services\Core\BaseService;
use Illuminate\Database\Eloquent\Builder;

class BranchOrWarehouseReportService extends BaseService
{
    public $filter;

    public function __construct(BranchOrWarehouse $branchOrWarehouse, BranchOrWarehouseFilter $filter)
    {
        $this->filter = $filter;
        $this->model = $branchOrWarehouse;
    }

    public function branchOrWarehouseQuery()
    {
        return $this->model->query()
            ->filters($this->filter)
            ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {
                $builder->where('id', request('branch_or_warehouse_id'));
            })
            ->withCount(['orders', 'lots', 'returnOrders', 'internalTransfers', 'adjustments'])
            ->addSelect(['total_selling_amount' => Order::selectRaw('sum(grand_total) as total')
                ->whereColumn('branch_or_warehouse_id', 'branch_or_warehouses.id')
                ->groupBy('branch_or_warehouse_id')
            ])
            ->orderBy('total_selling_amount', 'DESC');
    }

    public function branchOrWarehouseReport()
    {
        return $this->branchOrWarehouseQuery()->paginate(
            request('per_page' ?? 10)
        );
    }


}