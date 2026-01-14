<?php

namespace App\Exports;

use App\Models\Pos\Product\Product\Variant;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductsExport implements WithHeadings, FromQuery, WithMapping, WithEvents
{
    use Exportable;

    public function query()
    {
        return Variant::query()->with('product');
    }

    public function map($variant): array
    {
        return [
            $variant->name ?? null,
            $variant->upc ?? null,
            $variant->selling_price ?? null,
            $variant->stock_reminder_quantity ?? null,
            $variant->description ?? null,
            $variant->tax->percentage ?? null,
            $variant->product->product_type ?? null,

            $variant->product->product_type === 'variant' ?  $this->mapAttrVariations($variant->attributesVariations) : '--',

            $variant->product->category->name ?? null,
            $variant->product->subCategory->name ?? null,
            $variant->product->group->name ?? null,
            $variant->product->brand->name ?? null,
            $variant->product->unit->name ?? null,

            //warranty duration in same column
            $variant->product->warranty_duration ? ($variant->product->warranty_duration.'-'.($variant->product->duration->type ?? '')) : ''
        ];
    }

    private function mapAttrVariations($attrVariations)
    {
        $singleAttrV = '';
        foreach ($attrVariations as $attrV){
            $singleAttrV .= $attrV->name. ',';
        }
        $singleAttrV =  substr($singleAttrV, 0, -1);
        return $singleAttrV;
    }



    public function headings(): array
    {
        return [
            __t('name'),
            __t('upc'),
            __t('selling_price'),
            __t('stock_reminder_quantity'),
            __t('description'),
            __t('tax(%)'),
            __t('product_type'),
            __t('attribute_variant'),
            __t('category'),
            __t('sub_category'),
            __t('group'),
            __t('brand'),
            __t('unit'),
            __t('warranty_duration'),
        ];
    }

    //Heilight heading column
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:N1')->applyFromArray([
                        'font' =>[
                            'bold' => true
                        ],
                        'borders' => [
                            'outline' =>[
                                'color' => ['argb' =>'FFFF0000']
                            ],
                        ]
                    ],
                );
            },
        ];
    }
}
