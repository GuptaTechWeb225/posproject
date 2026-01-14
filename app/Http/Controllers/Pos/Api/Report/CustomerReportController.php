<?php

namespace App\Http\Controllers\Pos\Api\Report;

use App\Exports\CustomerReportExport;
use App\Filters\Tenant\CustomerFilter;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Customer\Customer;
use Maatwebsite\Excel\Facades\Excel;

class CustomerReportController extends Controller
{
    public $filter;
    public $model;
    public $excel;

    public function __construct(Customer $customer, CustomerFilter $customerFilter, Excel $excel)
    {
        $this->model = $customer;
        $this->filter = $customerFilter;
        $this->excel = $excel;
    }

    public function index()
    {
        $branch_or_warehouse_id = \request()->branch_or_warehouse_id;

        return $this->model->query()
            ->select('id','customer_group_id','email','tin','first_name', 'last_name')
            ->filters($this->filter)
            ->whereHas('orders', function ($query) use ($branch_or_warehouse_id){
                $branch_or_warehouse_id === 'null' ?  $query->where('branch_or_warehouse_id', '!=', null) : $query->where('branch_or_warehouse_id', $branch_or_warehouse_id);
             })
            ->with(
                'customerGroup:id,name',
               'orders',
               'orders.branchOrWarehouse:id,name,type'
            )
            ->withSum('orders as total_purchase', 'grand_total')
            ->withSum('orders as total_due', 'due_amount')
            ->latest('id')
            ->paginate(
                request('per_page', 15)
            );
    }

    public function exportAllCustomerByBranchOrWarehouse()
    {
        $response =  (new CustomerReportExport())->download('cash-counter-reports.xlsx');
        if (ob_get_contents()) ob_end_clean();
        return $response;
    }

}
