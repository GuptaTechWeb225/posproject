<?php

namespace App\Http\Controllers\Pos\Api\Inventory;

use App\Exceptions\GeneralException;
use App\Filters\Pos\Inventory\BranchOrWarehouseFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\Inventory\BranchOrWarehouse\BranchOrWarehouseRequest;
use App\Models\Core\Auth\User;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Pos\Inventory\BranchAndWarehouse\BranchAndWarehouseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchAndWarehouseController extends Controller
{

    public function __construct(BranchAndWarehouseService $service, BranchOrWarehouseFilter $filter)
    {
        $this->filter = $filter;
        $this->service = $service;
    }

    public function index()
    {
        $branches = $this->service
            ->filters($this->filter)
            ->select('id', 'manager_id', 'status_id', 'name', 'location', 'type', 'tax_id')
            ->permission()
            ->with([
                'status:id,name,class',
                'manager:id,first_name,last_name',
                'tax:id,percentage',
                'users',
                'users.roles:id,name',
            ])->latest();

        $branches = request()->active
            ? $branches->where(['status_id' => resolve(StatusRepository::class)->branchorwarehouseActive()])
            : $branches;

        return $branches->paginate(request()->get('per_page', 10));
    }


    public function store(BranchOrWarehouseRequest $request)
    {
        DB::transaction(function () use ($request) {
            $status = resolve(StatusRepository::class)->branchorwarehouseActive();
            $attributes = array_merge($request->only('name', 'manager_id', 'location', 'type'), [
                'status_id' => $status
            ]);

            $this->service
                ->storeBranchOrWarehouse($attributes)
                ->createDefaultCashRegister();
        });

        return created_responses('branchOrWarehouse');
    }

    public function show(BranchOrWarehouse $branchOrWarehouse): BranchOrWarehouse
    {
        return $branchOrWarehouse
            ->load('tax:id,name,percentage');
    }

    public function update(BranchOrWarehouse $branchOrWarehouse, BranchOrWarehouseRequest $request)
    {
        DB::transaction(function () use ($branchOrWarehouse, $request) {
            $this->service
                ->setModel($branchOrWarehouse)
                ->save();
        });
        return updated_responses('branchOrWarehouse');
    }


    public function destroy(BranchOrWarehouse $branchOrWarehouse)
    {
        $this->service
            ->setModel($branchOrWarehouse)
            ->delete();

        return deleted_responses('branchOrWarehouse');
    }

    public function branchOrWarehouseStatusChange(BranchOrWarehouse $id, Request $request)
    {
        $this->service
            ->setModel($id)
            ->setAttrs($request->only('status'))
            ->changeStatus();

        return updated_responses('status');
    }

    /**
     * @throws \Throwable
     */
    public function cheBranchWarehouseActive(): bool
    {
        throw_if(!request()->branch_or_warehouse_id, new GeneralException(__t('please_select_a_branch_or_warehouse')));
        return $this->service->checkBranchWarehouseActive();
    }

    public function manageBranchOrWarehouseUsers(BranchOrWarehouse $branch_or_warehouse_id, Request $request)
    {
        $request->validate([
            'users' => 'required|array'
        ]);

        foreach ($request->get('users') as $id){
            User::find($id)->update(['branch_or_warehouse_id' => $branch_or_warehouse_id->id]);
        }

        return updated_responses('branchOrWarehouse');
    }
}
