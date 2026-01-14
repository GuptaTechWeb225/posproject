<?php

namespace App\Http\Controllers\Tenant\Expense;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Expense\ExpenseArea;

class ExpenseAreaApiController extends Controller
{
    public function index()
    {
        return ExpenseArea::get(['id', 'name']);
    }
}
