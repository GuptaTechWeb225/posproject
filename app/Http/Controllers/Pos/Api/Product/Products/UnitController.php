<?php

namespace App\Http\Controllers\Pos\Api\Product\Products;

use App\Filters\Tenant\UnitFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Product\UnitRequest;
use App\Models\Pos\Product\Unit\Unit;
use App\Services\Tenant\Product\UnitService;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UnitController extends Controller
{
    public function __construct(UnitService $service, UnitFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index(): LengthAwarePaginator
    {
        return $this->service
            ->filters($this->filter)
            ->with('createdBy', 'status')
            ->latest()
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    public function store(UnitRequest $request)
    {
        $this->service
            ->save($request->only(['name', 'status_id']));
        return created_responses('unit');
    }

    public function show(Unit $unit)
    {
        return $unit->load('createdBy', 'status');
    }

    public function update(UnitRequest $request, Unit $unit)
    {
        $this->service
            ->setModel($unit)
            ->save($request->only(['name', 'status_id']));
        return updated_responses('unit', ['unit' => $unit]);
    }

    public function destroy(Unit $unit)
    {
        $countUsedProduct = $unit->products();
        if ($countUsedProduct->count() > 0){
            return failed_responses();
        }

        $this->service
            ->setModel($unit)
            ->delete();

        return deleted_responses('unit');
    }

    public function changeStatus(Unit $unit_id, Request $request)
    {
        $request->validate([
            'status_id' => 'required',
        ]);
        $unit_id->status_id = intval($request->status_id);
        $unit_id->save();

        return status_response('unit', strtolower(__t($unit_id->load('status')->status->name)));

    }
}
