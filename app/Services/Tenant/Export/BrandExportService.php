<?php

namespace App\Services\Tenant\Export;

use App\Models\Pos\Product\Brand\Brand;
use App\Services\Core\BaseService;
use Maatwebsite\Excel\Facades\Excel;

class BrandExportService extends BaseService
{
    public function __construct(Brand $brand)
    {
        $this->model = $brand;
    }
    public function download($batch = 0)
    {
        $export_count = config('excel.exports.chunk_size');

        $skip = ($export_count * $batch) - $export_count;

        $data = $this->mapper();

        $relation = ['createdBy'];

        $brands = getChunk($skip, $export_count, $this->model, $data, $relation);

        $title= __t('brands');

        //new
        $response =  Excel::download(exportBuilder
        (
            $brands,
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
        return fn($brand) => [
            'id' => $brand->id,
            'name' => $brand->name,
            'created_by' => $brand->createdBy->full_name
        ];
    }


}
