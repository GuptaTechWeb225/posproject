<?php

namespace App\Services\Tenant\Export;

use App\Models\Tenant\Supplier\Supplier;
use App\Services\Tenant\TenantService;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;

class SupplierExportService extends TenantService
{

    public function __construct(Supplier $supplier)
    {
        $this->model = $supplier;
    }

    public function download($batch = 0)
    {
        $export_count = config('excel.exports.chunk_size');

        $skip = ($export_count * $batch) - $export_count;

        $data = $this->mapper();

        $relation = ['contacts','createdBy'];

        $customers = getChunk($skip, $export_count, $this->model, $data, $relation);

        $title= __t('supplier');

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
            'ID', 'First Name', 'Last Name', 'Email', 'Phone number', 'Address', 'Company', 'Created by', 'Created At'
        ];
    }

    public function formatContact(Collection $contacts)
    {
        return collect(['phone_number', 'address', 'company'])
            ->map(function ($key) use ($contacts) {
                $contact = $contacts->where('name', $key)->first();
                return optional($contact)->value ?: 'N/A';
            })->toArray();

    }

    private function mapper()
    {
        return fn($supplier) => [
            $supplier->id,
            $supplier->first_name,
            $supplier->last_name,
            $supplier->email,
            ...$this->formatContact($supplier->contacts),
            $supplier->createdBy->first_name,
            $supplier->created_at,
        ];
    }

}
