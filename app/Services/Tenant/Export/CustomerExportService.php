<?php

namespace App\Services\Tenant\Export;

use App\Models\Tenant\Customer\Customer;
use App\Services\Tenant\TenantService;
use Maatwebsite\Excel\Facades\Excel;

class CustomerExportService extends TenantService
{

    public function __construct(Customer $customer)
    {
        $this->model = $customer;
    }

    public function download($batch = 0)
    {
        $export_count = config('excel.exports.chunk_size');

        $skip = ($export_count * $batch) - $export_count;

        $data = $this->mapper();

        $relation = ['customerGroup','createdBy'];

        $customers = getChunk($skip, $export_count, $this->model, $data, $relation);

        $title= __t('customer');

        return Excel::download(exportBuilder
            (
                $customers,
                $this->getHeadings(),
                $title
            ), "$title-$batch.xlsx"
        );

    }

    private function getHeadings()
    {
        return [
            'ID', 'First name', 'Last name', 'Details Group', 'Tin', 'Created by', 'Created At'
        ];
    }

    private function mapper()
    {
        return fn($customer) => [
            'id' => $customer->id,
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'customer_group' => $customer->customerGroup->name,
            'tin' => $customer->tin,
            'created_by' => $customer->createdBy->first_name,
            'created_at' => $customer->created_at,
        ];
    }

}
