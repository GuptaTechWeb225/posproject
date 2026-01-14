<?php


namespace App\Services\Pos\Inventory\InternalTransfer;

use App\Exceptions\GeneralException;
use App\Models\Pos\Inventory\InternalTransfer;
use App\Models\Pos\Inventory\InternalTransferVariant;
use App\Models\Tenant\Expense\Expense;
use App\Services\Pos\Inventory\Stock\StockService;
use App\Services\Tenant\Expense\ExpenseAreaService;
use App\Services\Tenant\TenantService;

class InternalTransferService extends TenantService
{
    public StockService $stockService;
    public ExpenseAreaService $expenseAreaService;

    public function __construct(InternalTransfer $internalTransfer, StockService $stockService, ExpenseAreaService $expenseAreaService)
    {
        $this->model = $internalTransfer;
        $this->stockService = $stockService;
        $this->expenseAreaService = $expenseAreaService;
    }

    public function relations(): array
    {
        return [
            'internalTransferVariants',
            'internalTransferVariants.variant:id,name,upc',
            'internalTransferVariants.variant.stock'
        ];
    }

    public function loadInternalTransferLists($filter)
    {
        return $this->model
            ->select('id', 'reference_no', 'status_id', 'branch_or_warehouse_from_id', 'branch_or_warehouse_to_id', 'date', 'note')
            ->branchOrWarehouse(request()->branch_or_warehouse_id)
            ->with(
                'internalTransferVariants',
                'status:id,name,class,type',
                'branchOrWarehouseFrom',
                'branchOrWarehouseTo'
            )
            ->withSum('internalTransferVariants as total_qty', 'unit_quantity')
            ->filters($filter)
            ->latest('id')
            ->paginate(
                request('per_page', 15)
            );
    }

    public function storeInternalTransferData(): static
    {
        $this->model = $this->saveInternalTransferInfo();
        return $this;
    }

    public function storeInternalTransferVariantData(): static
    {
        $this->saveInternalTransferVariantInfo();
        return $this;
    }

    private function saveInternalTransferVariantInfo(): void
    {
        $inTVariants = [];

        if ($this->getAttr('internalTransferVariants')) {
            foreach ($this->getAttr('internalTransferVariants') as $intV) {
                $inTVariants[] = $this->internalTransferVariantRequest($intV);
            }
        }

        $this->model->internalTransferVariants()->insert($inTVariants);
    }


    private function saveInternalTransferInfo(): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        return $this->model::query()
            ->create($this->internalTransferRequest());
    }

    private function internalTransferRequest(): array
    {
        return [
            'branch_or_warehouse_from_id' => $this->getAttr('branch_or_warehouse_from_id'),
            'branch_or_warehouse_to_id' => $this->getAttr('branch_or_warehouse_to_id'),
            'date' => $this->getAttr('date'),
            'total_transfer_cost' => $this->getAttr('total_transfer_cost'),
            'status_id' => $this->getAttr('status_id'),
            'created_by' => $this->getAttr('created_by'),
            'reference_no' => $this->getAttr('reference_no'),
            'note' => $this->getAttr('note'),
        ];
    }

    private function internalTransferVariantRequest($internalTransferVariants = null): array
    {
        return [
            'internal_transfer_id' => $this->model->id,
            'variant_id' => $internalTransferVariants['variant_id'],
            'unit_quantity' => $internalTransferVariants['unit_quantity'],
            'lot_reference_no' => $internalTransferVariants['lot_reference_no'],
        ];
    }

    public function deleteInternalTransferVariant(): static
    {
        $this->model->internalTransferVariants()->delete();
        return $this;
    }

    public function deleteInternalTransfer(): static
    {
        $this->model->delete();
        return $this;
    }

    public function updateInternalTransfer($options = [])
    {
        $attributes = count($options) ? $options : request()->all();
        $this->model
            ->fill($this->getFillAble($attributes))
            ->save();

        return $this;
    }

    public function UpdateInternalTransferVariant(): static
    {
        if ($this->getAttr('internalTransferVariants')) {
            foreach ($this->getAttr('internalTransferVariants') as $variant) {
                if (isset($variant['id'])) {
//                    update existing variant
                    $variantObj = InternalTransferVariant::find($variant['id']);
                    $variantObj->update($variant);
                } else {
                    //crate new InternalTransfer variant
                    $data['internal_transfer_id'] = $this->model->id;
                    $data['variant_id'] = $variant['variant_id'] ?? null;
                    $data['unit_quantity'] = $variant['unit_quantity'] ?? null;
                    $data['lot_reference_no'] = $variant['lot_reference_no'] ?? null;
                    InternalTransferVariant::query()->create($data);
                }
            }
        }

        return $this;
    }

    public function updateInternalTransferStatus(): static
    {
        $status_id = $this->getAttr('status_id');
        //only change lot status
        $this->model->update([
            'status_id' => $status_id
        ]);

        return $this;
    }


    public function updateStock($status_id_for_transfer_complete): static
    {
        $status_id = $this->getAttr('status_id');

        if ( ($this->model->adjusted_with_stock == false) and ($status_id_for_transfer_complete == $status_id)) {

            $variant = [];
            foreach ($this->model->InternalTransferVariants as $internalT_variant) {

                $variant['variant_id'] = $internalT_variant->variant_id;
                $variant['branch_or_warehouse_from_id'] = $this->model->branch_or_warehouse_from_id;
                $variant['branch_or_warehouse_to_id'] = $this->model->branch_or_warehouse_to_id;

                // internalTransfer can add if variant row exist in stock
                //sender
                $variantRowExistInStockForInternalTransferSender = $this->stockService
                    ->checkVariantExistInStock($internalT_variant['variant_id'], $this->model->branch_or_warehouse_from_id);

                //receiver
                $variantRowExistInStockForInternalTransferReceiver = $this->stockService
                    ->checkVariantExistInStock($internalT_variant['variant_id'], $this->model->branch_or_warehouse_to_id);

                //sender
                $oldStockForInternalTransferSender = $this->stockService
                    ->getStockByVariantId($internalT_variant['variant_id'], $this->model->branch_or_warehouse_from_id);

                //receiver
                $oldStockForInternalTransferReceiver = $this->stockService
                    ->getStockByVariantId($internalT_variant['variant_id'], $this->model->branch_or_warehouse_to_id);

                throw_if(!$variantRowExistInStockForInternalTransferSender, new GeneralException(__t('internal_transfer_only_possible_for_existing_variant_in_stock')));


                //available_qty for sender
                $variant['available_qty_for_sender'] = $oldStockForInternalTransferSender->available_qty - $internalT_variant['unit_quantity'];

                $variant['available_qty_for_receiver'] = $variantRowExistInStockForInternalTransferReceiver
                    ? $oldStockForInternalTransferReceiver->available_qty + $internalT_variant['unit_quantity']
                    : $internalT_variant['unit_quantity'];

                $variant['total_internal_transfer_sent_qty'] = $internalT_variant->unit_quantity + $oldStockForInternalTransferSender->total_internal_transfer_sent_qty;

                $variant['total_internal_transfer_received_qty'] = $variantRowExistInStockForInternalTransferReceiver
                    ? $internalT_variant->unit_quantity + $oldStockForInternalTransferReceiver->total_internal_transfer_received_qty
                    : $internalT_variant->unit_quantity;

                $this->stockService->updateOrCreateStockAfterCompleteInternalTransfer($variant);

                //update lot table for adjusting stocks
                $this->model->update([
                    'adjusted_with_stock' => 1
                ]);
            }
        }

        return $this;
    }


    public function addExpenseForInternalTransfer(): static
    {
        if ($this->model->adjusted_with_stock == true){
            //create Expense Area if not exist
            $this->expenseAreaService->createExpenseArea('Internal transfer');

            //get expense area data by name
            $expense_area = $this->expenseAreaService->getExpenseAreaByName('Internal transfer');

            Expense::query()->create([
                'expense_area_id' => $expense_area->id,
                'name' => $expense_area->name ?? '',
                'amount' => $this->model->total_transfer_cost ?? 0,
                'branch_or_warehouse_id'=> $this->model->branch_or_warehouse_to_id ?? null,
                'description' => $this->model->note ?? '',
                'expense_date' => $this->model->date ?? null
            ]);
        }

        return $this;
    }

}