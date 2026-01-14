<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithChunkReading
{
    use Exportable;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function title(): string
    {
        return 'Sales Report';
    }

    public function chunkSize(): int
    {
        return 100;
    }


    public function collection()
    {
        return $this->order->get();
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->invoice_number,
            $order->createdBy->full_name,
            $order->ordered_at,
            $order->grand_total,
        ];
    }

    public function headings(): array
    {
        return [
            __t('id'),
            __t('invoice_number'),
            __t('customer_name'),
            __t('ordered_at'),
            __t('grand_total'),
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                    'borders' => [
                        'outline' => [
                            'color' => ['argb' => 'FFFF0000']
                        ],
                    ]
                ],
                );
            },
        ];
    }

}
