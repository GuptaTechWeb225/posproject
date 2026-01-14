<?php

namespace App\Services\Tenant\Export;

use App\Models\Pos\Product\Group\Group;
use App\Services\Core\BaseService;
use Maatwebsite\Excel\Facades\Excel;

class GroupExportService extends BaseService
{
    public function __construct(Group $group)
    {
        $this->model = $group;
    }
    public function download($batch = 0)
    {
        $export_count = config('excel.exports.chunk_size');

        $skip = ($export_count * $batch) - $export_count;

        $data = $this->mapper();

        $relation = ['createdBy'];

        $customers = getChunk($skip, $export_count, $this->model, $data, $relation);

        $title= __t('groups');

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
            __t('id'), __t('name'), __t('created_by')
        ];
    }

    private function mapper()
    {
        return fn($group) => [
            'id' => $group->id,
            'name' => $group->name,
            'created_by' => $group->createdBy->full_name
        ];
    }


}
