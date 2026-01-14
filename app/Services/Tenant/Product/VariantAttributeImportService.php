<?php


namespace App\Services\Tenant\Product;

use App\Helpers\Core\Traits\Memoization;
use App\Managers\BulkImport\Product\VariantAttribute\VariantAttributeImport;
use App\Models\Pos\Product\Attribute\Attribute;
use App\Services\Core\BaseService;
use Illuminate\Support\Facades\DB;

class VariantAttributeImportService extends BaseService
{
    use Memoization;

    private VariantAttributeImport $importer;


    public function __construct(Attribute $variantAttribute)
    {
        $this->model = $variantAttribute;
        $this->importer = resolve(VariantAttributeImport::class);
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
        $variantAttributes = $this->getAttr('variantAttributes');


        DB::transaction(function () use ($variantAttributes) {

            foreach ($variantAttributes as $key => $group) {
                $this->storeVariantAttributes($group);
            }

        });
    }


    private function storeVariantAttributes($variantAttribute): int
    {

        $createVariantAttribute= $this->model
            ->create([
                "name" => $variantAttribute['name'],
            ]);
        return $createVariantAttribute->id;

    }


    public function store()
    {

        $variantAttributes = $this->importer->sanitize(
            $this->getAttr('variantAttributes')
        );

        $count = 0;

        if (filled($variantAttributes['sanitized'])) {

            DB::transaction(function () use ($variantAttributes) {

                foreach ($variantAttributes['sanitized'] as $key => $group) {
                    $this->storeVariantAttributes($group);
                }

            });
        }

        $this->unsetErrorBag($variantAttributes);

        return $count;
    }

    private function unsetErrorBag($variantAttributes)
    {
        unset($variantAttributes['sanitized']);

        if (count($variantAttributes['filtered'])) {
            $columns = array_keys($variantAttributes["filtered"][0]);
            unset($columns[array_search("errorBag", $columns)]);
            return array_merge($variantAttributes, ["columns" => $columns]);
        }
    }

}