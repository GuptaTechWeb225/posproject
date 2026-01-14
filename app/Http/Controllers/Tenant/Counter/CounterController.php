<?php

namespace App\Http\Controllers\Tenant\Counter;

use App\Exceptions\GeneralException;
use App\Filters\Tenant\CashRegisterFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Counter\CounterRequest;
use App\Models\Tenant\Sales\Cash\CashRegister;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\Counter\CounterService;
use Illuminate\Support\Facades\DB;

class CounterController extends Controller
{

    public function __construct(CounterService $service, CashRegisterFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->query()
            ->filters($this->filter)
            ->with(
                'status:id,name,class',
                'createdBy:id,first_name,last_name,email,status_id',
                'createdBy.status:id,name,class,type',
                'createdBy.roles:id,name,type_id',
                'createdBy.profilePicture:path,fileable_type,fileable_id',
                'branchOrWarehouse:id,name',
                'openedBy:id,first_name,last_name,email,status_id',
                'openedBy.status:id,name,class,type',
                'openedBy.roles:id,name,type_id',
                'openedBy.profilePicture:path,fileable_type,fileable_id',
                'closedBy:id,first_name,last_name,email,status_id',
                'closedBy.status:id,name,class,type',
                'closedBy.roles:id,name,type_id',
                'closedBy.profilePicture:path,fileable_type,fileable_id',
            )
            ->select('id', 'branch_or_warehouse_id', 'name', 'opening_time', 'closing_time', 'opening_balance',
                'closing_balance', 'created_by', 'status_id', 'opened_by', 'closed_by')
            ->paginate(request()->per_page ?? 15);
    }

    public function store(CounterRequest $request)
    {
        DB::transaction(function () use ($request) {
            $status = resolve(StatusRepository::class)->cash_counterClose();
            $attributes = array_merge($request->only('name', 'branch_or_warehouse_id'), [
                'status_id' => $status,
                'multiple_access' => true
            ]);
            $this->service->query()->create($attributes);
        });

        return created_responses('counter');
    }

    public function show(CashRegister $counter)
    {
        return $counter->load('branchOrWarehouse:id,name');
    }

    public function update(CashRegister $counter, CounterRequest $request)
    {
        DB::transaction(function () use ($counter, $request) {
            $counter->update(
                $request->only('name', 'branch_or_warehouse_id')
            );
        });

        return updated_responses('counter');
    }

    public function destroy(CashRegister $counter)
    {
        try {
            $counter->delete();
            return deleted_responses('counter');
        }catch (\Exception $exception){
            throw new GeneralException(trans('default.counter_is_in_use'));
        }
    }
}
