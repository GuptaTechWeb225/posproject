<?php

namespace App\Imports;

use App\Services\Import\LotImportService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LotImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public $lot_id;

    public function __construct($lot_id)
    {
        $this->lot_id = $lot_id;
    }

    public function model(array $row)
    {
        DB::transaction(
            fn() => resolve(LotImportService::class)
                ->setAttrs(array_merge($row, ['lot_id' => $this->lot_id]))
                ->storeLotVariantData()
        );
    }

    public array $requiredHeading = [
        "variant_name",
        "unit_quantity",
        "unit_price",
        "unit_tax_percentage"
    ];

    public function rules(): array
    {
        return [
            '*.variant_name' => ['required', 'string'],
            '*.unit_quantity' => ['required', 'string'],
            '*.unit_price' => ['required', 'string'],
            '*.unit_tax_percentage' => ['required', 'string'],
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
