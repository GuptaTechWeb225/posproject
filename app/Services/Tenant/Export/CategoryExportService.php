<?php

namespace App\Services\Tenant\Export;

use App\Models\Pos\Product\Category\Category;
use App\Services\Core\BaseService;
use Maatwebsite\Excel\Facades\Excel;

class CategoryExportService extends BaseService
{
    public function __construct(Category $category)
    {
        $this->model = $category;
    }
    public function download($batch = 0)
    {

        $export_count = config('excel.exports.chunk_size');

        $skip = ($export_count * $batch) - $export_count;

        $data = $this->mapper();

        $relation = ['createdBy'];

        $categories = getChunk($skip, $export_count, $this->model, $data, $relation);

        $title= __t('categories');

        return Excel::download(exportBuilder
        (
            $categories,
            $this->getHeadings(),
            $title
        ), "$title-$batch.xlsx"
        );
    }
    private function getHeadings()
    {
        return [
            __t('id'), __t('name'), __t('created_by')
        ];
    }

    private function mapper()
    {
        return fn($category) => [
            'id' => $category->id,
            'name' => $category->name,
            'created_by' => $category->createdBy->full_name,
        ];
    }


}
