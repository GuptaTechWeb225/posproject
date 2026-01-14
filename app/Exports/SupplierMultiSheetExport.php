<?php


namespace App\Exports;


use App\Models\Tenant\Supplier\Supplier;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SupplierMultiSheetExport implements FromCollection, WithMapping, WithHeadings, WithTitle
{

    private int $chunkSize;


    public function __construct(int $chunkSize)
    {
        $this->chunkSize = $chunkSize;
    }


    public function title(): string
    {
        return 'Supplier Lists';
    }


    public function collection()
    {
        return Supplier::take(20000)->get();
    }


    public function map($supplier): array
    {
        return [
            $supplier->id,
            $supplier->first_name,
            $supplier->last_name,
            $supplier->email,
            ...$this->formatContact($supplier->contacts),
            $supplier->createdBy->first_name,
            $supplier->created_at,
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


    public function headings(): array
    {
        return [
            'ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone number',
            'Address',
            'Company',
            'Created by',
            'Created At',
        ];
    }


}