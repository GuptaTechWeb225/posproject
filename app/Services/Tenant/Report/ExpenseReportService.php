<?php

namespace App\Services\Tenant\Report;

use App\Models\Tenant\Expense\Expense;
use App\Services\Core\BaseService;

class ExpenseReportService extends BaseService
{
    public function __construct(Expense $expense)
    {
        $this->model = $expense;
    }

    public function expenseReport()
    {

    }
}