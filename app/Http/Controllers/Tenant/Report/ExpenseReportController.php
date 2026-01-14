<?php

namespace App\Http\Controllers\Tenant\Report;

use App\Filters\Tenant\ExpenseFilter;
use App\Http\Controllers\Controller;
use App\Services\Tenant\Report\ExpenseReportService;
use Illuminate\Database\Eloquent\Builder;

class ExpenseReportController extends Controller
{
    public $filter;

    public function __construct(ExpenseReportService $service, ExpenseFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
             ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {
                $builder->where('branch_or_warehouse_id', request('branch_or_warehouse_id'));
            })
            ->with([
                'expenseArea:id,name',
                'attachments',
                'branchOrWarehouse:id,name,type',
            ])
            ->latest('id')
            ->paginate(
                request('per_page', 10)
            );
    }

}
