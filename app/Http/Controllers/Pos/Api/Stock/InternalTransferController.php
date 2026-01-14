<?php

namespace App\Http\Controllers\Pos\Api\Stock;

use App\Exceptions\GeneralException;
use App\Filters\Pos\Inventory\InternalTransferFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\Inventory\InternalTransfer\InternalTransferRequest;
use App\Models\Pos\Inventory\InternalTransfer;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Pos\Inventory\InternalTransfer\InternalTransferService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternalTransferController extends Controller
{
    public function __construct(InternalTransferService $internalTransferService, InternalTransferFilter $internalTransferFilter)
    {
        $this->service = $internalTransferService;
        $this->filter = $internalTransferFilter;
    }

    public function index(): LengthAwarePaginator
    {
        return $this->service
            ->loadInternalTransferLists($this->filter);
    }


    public function store(InternalTransferRequest $request)
    {
        DB::transaction(function () use ($request) {
            $this->service
                ->setAttrs($request->all())
                ->storeInternalTransferData()
                ->storeInternalTransferVariantData();
        });

        return created_responses('internal_transfer');
    }

    public function show(InternalTransfer $internalTransfer): InternalTransfer
    {
        return $internalTransfer->load($this->service->relations());
    }

    public function update(InternalTransferRequest $request, InternalTransfer $internalTransfer)
    {
        $status_id_for_transfer_complete = resolve(StatusRepository::class)->internaltransferComplete();

        //completed internal transfer can't update
        throw_if($internalTransfer->status_id == $status_id_for_transfer_complete, new GeneralException(__t('completed_internal_transfer_not_allowed_to_update')));

        DB::transaction(function () use ($request, $internalTransfer) {
            $this->service
                ->setModel($internalTransfer)
                ->setAttrs($request->all())
                ->updateInternalTransfer(
                    $request->only('branch_or_warehouse_from_id', 'branch_or_warehouse_to_id', 'date','adjusted_with_stock',
                        'status_id', 'total_transfer_cost', 'created_by', 'reference_no', 'note')
                )
                ->UpdateInternalTransferVariant();
        });

        return updated_responses('internal_transfer');
    }


    public function destroy(InternalTransfer $internalTransfer)
    {
        $status_id_for_transfer_complete = resolve(StatusRepository::class)->internaltransferComplete();

        //completed internal transfer can't delete
        throw_if($internalTransfer->status_id == $status_id_for_transfer_complete, new GeneralException(__t('completed_internal_transfer_not_allowed_to_delete')));

        DB::transaction(function () use ($internalTransfer) {
            $this->service
                ->setModel($internalTransfer)
                ->deleteInternalTransferVariant()
                ->deleteInternalTransfer();
        });

        return deleted_responses('lot');
    }

    //if Internal transfer status is complete then update stock too
    public function changeInternalTransferStatus(Request $request, $internal_transfer_id)
    {
        $request->validate([
            'status_id' => 'required'
        ]);

        $internalTransfer = $this->service->find($internal_transfer_id);
        $status_id_for_transfer_complete = resolve(StatusRepository::class)->internaltransferComplete();

        //Delivered status can't change to others
        throw_if($internalTransfer->status_id == $status_id_for_transfer_complete, new GeneralException(__t('completed_internal_transfer_not_allowed_to_update')));

        DB::transaction(function () use ($status_id_for_transfer_complete, $internalTransfer, $request) {
            $this->service
                ->setModel($internalTransfer)
                ->setAttrs($request->all())
                ->updateInternalTransferStatus()
                ->updateStock($status_id_for_transfer_complete)
                ->addExpenseForInternalTransfer();
        });

        return updated_responses('status');
    }
}
