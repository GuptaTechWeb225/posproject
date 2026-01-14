<?php


namespace App\Services\Tenant\Product;

use App\Helpers\Core\Traits\Memoization;
use App\Managers\BulkImport\Product\Brand\BrandImport;
use App\Models\Pos\Product\Brand\Brand;
use App\Services\Core\BaseService;
use Illuminate\Support\Facades\DB;

class BrandImportService extends BaseService
{
    use Memoization;

    private BrandImport $importer;


    public function __construct(Brand $brand)
    {
        $this->model = $brand;
        $this->importer = resolve(BrandImport::class);
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
        $brands = $this->getAttr('brands');


        DB::transaction(function () use ($brands) {

            foreach ($brands as $key => $group) {
                $this->storeBrands($group);
            }

        });
    }


    private function storeBrands($brand): int
    {

        $createBrand = $this->model
            ->create([
                "name" => $brand['name'],
            ]);
        return $createBrand->id;

    }


    public function store()
    {

        $brands = $this->importer->sanitize(
            $this->getAttr('brands')
        );

        $count = 0;

        if (filled($brands['sanitized'])) {

            DB::transaction(function () use ($brands) {

                foreach ($brands['sanitized'] as $key => $group) {
                    $this->storeBrands($group);
                }

            });
        }

        $this->unsetErrorBag($brands);

        return $count;
    }

    private function unsetErrorBag($brands)
    {
        unset($brands['sanitized']);

        if (count($brands['filtered'])) {
            $columns = array_keys($brands["filtered"][0]);
            unset($columns[array_search("errorBag", $columns)]);
            return array_merge($brands, ["columns" => $columns]);
        }
    }

}