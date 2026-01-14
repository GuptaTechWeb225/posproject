<?php

namespace App\Http\Controllers\Tenant\Expense;

use App\Filters\Tenant\ExpenseFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Expense\ExpenseRequest;
use App\Models\Core\File\File;
use App\Models\Tenant\Expense\Expense;
use App\Services\Tenant\Expense\ExpenseService;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function __construct(ExpenseService $services, ExpenseFilter $filter)
    {
        $this->service = $services;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->branchOrWarehouse(request()->branch_or_warehouse_id)
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

    public function store(ExpenseRequest $request)
    {
        DB::transaction(function () use ($request) {

            $expense = $this->service
                ->save($request->only('name', 'expense_area_id', 'amount', 'description', 'expense_date', 'branch_or_warehouse_id'));

            if ($request->attachments) {
                $this->service
                    ->setModel($expense)
                    ->setAttrs($request->only('attachments'))
                    ->storeAttachments();
            }
        });

        return created_responses('expense');
    }

    public function show(Expense $expense)
    {
        return $expense->load('expenseArea:id,name', 'attachments');
    }

    public function update(ExpenseRequest $request, Expense $expense)
    {
        $this->service
            ->setModel($expense)
            ->setAttributes(
                array_merge(
                    [
                        'description' => $request->description == 'null' || empty($request->description) || is_null($request->description) ? null : $request->description
                    ],
                    $request->only('name', 'expense_area_id', 'amount', 'expense_date', 'branch_or_warehouse_id')
                )
            )
            ->save();

        if ($request->only('attachments')) {
            $this->service
                ->setModel($expense)
                ->setAttrs($request->only('attachments'))
                ->storeAttachments();
        }

        return updated_responses('expense');
    }

    public function destroy(Expense $expense)
    {
        $this->service
            ->setModel($expense)
            ->deleteAttachmentFile($expense->attachments)
            ->delete();

        return deleted_responses('expense');
    }

    public function deleteFile($id)
    {
        $file = File::query()
            ->where('id', $id)
            ->where('type', 'expense')
            ->first();

        if (isset($file->path)){
            $this->service->deleteImage($file->path);
            $file->delete();
            return deleted_responses('Expense attachment');
        }else{
            return failed_responses();
        }
    }
}
