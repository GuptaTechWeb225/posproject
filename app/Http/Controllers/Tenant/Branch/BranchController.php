<?php

namespace App\Http\Controllers\Tenant\Branch;

use App\Exceptions\GeneralException;
use App\Filters\Tenant\BranchFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Branch\BranchRequest;
use App\Models\Tenant\Branch\Branch;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\Branch\BranchService;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function __construct(BranchService $service, BranchFilter $filter)
    {
        $this->filter = $filter;
        $this->service = $service;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->with(['status:id,name,class', 'manager:id,first_name,last_name', 'counters.salesPeople.salesPerson', 'counters.invoiceTemplate.createdBy', 'counters.status'])
            ->latest()
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    public function store(BranchRequest $request)
    {
        $branch = DB::transaction(function () use ($request) {
            $status = resolve(StatusRepository::class)->branchActive();
            $attributes = array_merge($request->only('name', 'manager_id', 'location'), [
                'status_id' => $status
            ]);
            $branch = $this->service
                ->setAttributes($attributes)
                ->save();

            $this->service
                ->saveCashRegister($branch);

            return $branch;
        });

        return created_responses('branch', ['branch' => $branch]);
    }

    public function show(Branch $branch)
    {
        return $branch;
    }

    public function update(BranchRequest $request, Branch $branch)
    {
        $this->service
            ->setModel($branch)
            ->save();
        return updated_responses('branch', ['branch' => $branch]);
    }

    public function destroy(Branch $branch)
    {
        $branchCount = $branch->counters->count();

        throw_if($branchCount > 0, new GeneralException(__t('the_branch_is_in_use')));

        throw_if($branch->is_default, new GeneralException(__t('the_branch_is_in_use')));
        throw_if(is_null($branch->created_at), new GeneralException(trans('default.action_not_allowed')));

        $this->service
            ->setModel($branch)
            ->delete();

        return deleted_responses('branch');
    }

    public function branchStatus()
    {
        return $this->service
            ->getBranchStatus();
    }
}
