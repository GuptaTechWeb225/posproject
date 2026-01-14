<?php

namespace App\Http\Controllers\Tenant\Contacts;

use App\Exports\SupplierMultiSheetExport;
use App\Filters\Tenant\SupplierFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Contact\SupplierRequest;
use App\Models\Tenant\Supplier\Supplier;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\Contact\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends Controller
{

    public function __construct(SupplierService $service, SupplierFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index(): object
    {
        return $this->service
            ->filters($this->filter)
            ->with([
                'emails',
                'phoneNumbers',
                'addresses:id,name,city,zip_code,area,details,country_id,supplier_id',
                'addresses.country:id,name',
                'createdBy:id,first_name,last_name',
                'profilePicture',
                'status:id,name,class,type'
            ])
            ->orderByDesc('id')
            ->select(['id', 'name', 'supplier_no', 'created_by', 'status_id'])
            ->paginate(
                request('per_page', 10)
            );
    }

    public function store(SupplierRequest $request): array
    {
        $activeStatusId = resolve(StatusRepository::class)->supplierActive();

        $supplier = DB::transaction(function () use ($request, $activeStatusId) {
            $supplier = $this->service
                ->setAttrs(array_merge(
                    ['status_id' => $activeStatusId],
                    $request->only('name', 'supplier_no')
                ))
                ->store();

            if ($request->get('address_information_details')) {
                $this->service
                    ->setAttrs($request->only('address_information_details'))
                    ->storeAddress();
            }

            $this->service
                ->setAttrs($request->only('email_details', 'phone_number_details'))
                ->storeContacts();

            return $supplier;
        });

        return created_responses('supplier', ['supplier' => $supplier]);
    }

    public function show(Supplier $supplier): object
    {
        $supplierInfo = $this->service->query()
            ->with(['contacts',
                'addresses:supplier_id,country_id,name,city,zip_code,area,details',
                'addresses.country',
                'emails',
                'phoneNumbers',
                'profilePicture',
                'lots'
            ])
            ->withCount('lots')
            ->find($supplier->id);
        return $supplierInfo;
    }

    public function update(Supplier $supplier, SupplierRequest $request)
    {
        $supplier = DB::transaction(function () use ($request, $supplier) {
            $supplier = $this->service
                ->setModel($supplier)
                ->save(
                    $request->only('supplier_no', 'name')
                );

            $this->service
                ->setModel($supplier)
                ->setAttrs($request->all())
                ->updateContacts();

            if ($request->get('address_information_details')) {
                $this->service
                    ->setModel($supplier)
                    ->setAttrs($request->only('address_information_details'))
                    ->updateAddress();
            }

            return $supplier;
        });

        return updated_responses('supplier', [
            'supplier' => $supplier
        ]);
    }

    public function destroy(Supplier $supplier): array
    {
        $supplier->contacts()->delete();

        $supplier->addresses()->delete();

        $supplier->delete();

        return deleted_responses('supplier');
    }

    public function excel()
    {
        return Excel::download((new SupplierMultiSheetExport(1000)), 'supplier.xlsx');
    }


    public function supplierStatusChange(Request $request, Supplier $supplier)
    {
        $this->service
            ->setModel($supplier)
            ->setAttrs($request->only('status'))
            ->changeStatus();

        return updated_responses('status');
    }

}
