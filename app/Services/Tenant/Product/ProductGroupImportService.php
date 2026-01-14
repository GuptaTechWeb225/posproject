<?php


namespace App\Services\Tenant\Product;

use App\Helpers\Core\Traits\Memoization;
use App\Managers\BulkImport\Product\Group\GroupImport;
use App\Models\Pos\Product\Group\Group;
use App\Services\Core\BaseService;
use Illuminate\Support\Facades\DB;

class ProductGroupImportService extends BaseService
{
    use Memoization;

    private GroupImport $importer;


    public function __construct(Group $group)
    {
        $this->model = $group;
        $this->importer = resolve(GroupImport::class);
    }

    public function preview(): array
    {
        $file = $this->getAttr('file');
        return $this->importer
            ->setFile($file)
            ->preview();

    }


    public function saveSanitized(): void
    {
        $groups = $this->getAttr('groups');


        DB::transaction(function () use ($groups) {

            foreach ($groups as $key => $group) {
                $this->storeGroups($group);
            }

        });
    }


    private function storeGroups($group): int
    {

        $createdGroup = $this->model
            ->create([
                "name" => $group['name'],
            ]);
        return $createdGroup->id;

    }


    public function store()
    {

        $groups = $this->importer->sanitize(
            $this->getAttr('productGroups')
        );

        $count = 0;

        if (filled($groups['sanitized'])) {

            DB::transaction(function () use ($groups) {

                foreach ($groups['sanitized'] as $key => $group) {
                    $this->storeGroups($group);
                }

            });
        }

        $this->unsetErrorBag($groups);

        return $count;
    }

    private function unsetErrorBag($groups)
    {
        unset($groups['sanitized']);

        if (count($groups['filtered'])) {
            $columns = array_keys($groups["filtered"][0]);
            unset($columns[array_search("errorBag", $columns)]);
            return array_merge($groups, ["columns" => $columns]);
        }
    }

}