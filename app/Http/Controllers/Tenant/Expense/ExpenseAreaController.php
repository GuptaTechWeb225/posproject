<?php

namespace App\Http\Controllers\Tenant\Expense;

use App\Exceptions\GeneralException;
use App\Filters\Tenant\ExpenseAreaFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Expense\ExpenseAreaRequest;
use App\Models\Tenant\Expense\Expense;
use App\Models\Tenant\Expense\ExpenseArea;
use App\Services\Tenant\Expense\ExpenseAreaService;
use Illuminate\Support\Facades\DB;

class ExpenseAreaController extends Controller
{
    public function __construct(ExpenseAreaService $service, ExpenseAreaFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->service
            ->filters($this->filter)
            ->addSelect(['total_amount' => Expense::query()
                ->whereColumn('expense_area_id', 'expense_areas.id')
                ->selectRaw(DB::raw('sum(amount) as total_amount'))
            ])
            ->latest('id')
            ->paginate(
                request('per_page', 10)
            );
    }

    public function store(ExpenseAreaRequest $request)
    {
        $this->service
            ->save($request->only('name', 'description', 'is_add_to_report', 'tenant_id'));

        return created_responses('area_of_expense');
    }

    public function show(ExpenseArea $expenseArea)
    {
        return $expenseArea;
    }

    public function update(ExpenseAreaRequest $request, ExpenseArea $expenseArea)
    {
        $this->service
            ->setModel($expenseArea)
            ->save($request->only('name', 'description', 'is_add_to_report', 'tenant_id'));

        return updated_responses('area_of_expense');
    }

    public function destroy(ExpenseArea $expenseArea)
    {
        $expenseAreaCount = $expenseArea->expenses()->count();

        throw_if($expenseAreaCount > 0, new GeneralException(__t('in_use', ['field' => 'area_of_expense'])));

        $expenseArea->expenses()->delete();

        $this->service
            ->setModel($expenseArea)
            ->delete();
        return deleted_responses('area_of_expense');
    }

}
