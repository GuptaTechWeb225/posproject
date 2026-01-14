<?php

namespace App\Services\Tenant\Export;

use App\Models\Pos\Product\Attribute\Attribute;
use App\Services\Core\BaseService;
use Maatwebsite\Excel\Facades\Excel;

class VariantAttributeExportService extends BaseService
{
    public function __construct(Attribute $attribute)
    {
        $this->model = $attribute;
    }
    public function download($batch = 0)
    {
        $export_count = config('excel.exports.chunk_size');

        $skip = ($export_count * $batch) - $export_count;

        $data = $this->mapper();

        $relation = ['createdBy'];

        $customers = getChunk($skip, $export_count, $this->model, $data, $relation);

        $title= __t('attributes');

        $response =  Excel::download(exportBuilder
        (
            $customers,
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
        return fn($group) => [
            'id' => $group->id,
            'name' => $group->name,
            'created_by' => $group->createdBy->full_name,
        ];
    }


}
