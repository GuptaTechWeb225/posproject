<?php

namespace App\Services\Tenant\Export;

use App\Models\Pos\Product\Unit\Unit;
use App\Services\Core\BaseService;
use Maatwebsite\Excel\Facades\Excel;

class UnitExportService extends BaseService
{
    public function __construct(Unit $unit)
    {
        $this->model = $unit;
    }
    public function download($batch = 0)
    {
        $export_count = config('excel.exports.chunk_size');

        $skip = ($export_count * $batch) - $export_count;

        $data = $this->mapper();

        $relation = ['createdBy'];

        $units = getChunk($skip, $export_count, $this->model, $data, $relation);

        $title= __t('units');

        //new
        $response = Excel::download(exportBuilder
        (
            $units,
            $this->getHeadings(),
            $title
        ), "$title-$batch.xlsx", \Maatwebsite\Excel\Excel::XLSX
        );

        if (ob_get_contents()) ob_end_clean();
        return $response;
    }
    private function getHeadings()
    {
        return [
            __t('id'), __t('name'), __t('created_by')
        ];
    }

    private function mapper()
    {
        return fn($unit) => [
            'id' => $unit->id,
            'name' => $unit->name,
            'created_by' => $unit->createdBy->full_name,
        ];
    }


}
