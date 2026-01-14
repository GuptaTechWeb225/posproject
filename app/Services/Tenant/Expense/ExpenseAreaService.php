<?php


namespace App\Services\Tenant\Expense;


use App\Models\Tenant\Expense\ExpenseArea;
use App\Services\Tenant\TenantService;

class ExpenseAreaService extends TenantService
{
    public function __construct(ExpenseArea $expenseArea)
    {
        $this->model = $expenseArea;
    }

    public function createExpenseArea($name)
    {
        $exist = $this->checkExpenseAreaExists('Internal transfer');

        !$exist ? ExpenseArea::query()->create([
            'name'=> $name
        ]) : null;
    }

    public function checkExpenseAreaExists($area_name)
    {
        return ExpenseArea::query()->whereName($area_name)->exists();
    }

    public function getExpenseAreaByName($area_name)
    {
        return ExpenseArea::query()->whereName($area_name)->first();
    }
}