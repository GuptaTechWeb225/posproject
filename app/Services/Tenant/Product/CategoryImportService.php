<?php


namespace App\Services\Tenant\Product;


use App\Helpers\Core\Traits\Memoization;
use App\Managers\BulkImport\Product\Category\CategoryImport;
use App\Models\Pos\Product\Category\Category;
use App\Services\Core\BaseService;

class CategoryImportService extends BaseService
{
    use Memoization;

    private CategoryImport $importer;

    public function __construct(Category $category)
    {
        $this->model = $category;
        $this->importer = resolve(CategoryImport::class);
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
        $categories = $this->getAttr('categories');

        $this->storeCategories($categories);
    }

    private function storeCategories($categories)
    {
        $validCategories = [];

        foreach ($categories as $key => $category) {

            $validCategories[] = [
                'name' => $category['name'],
                'created_by' => auth()->id()
            ];

        }

        Category::query()->insert($validCategories);
    }


    public function store()
    {
        $categories = $this->importer->sanitize(
            $this->getAttr('categories')
        );

        $count = 0;

        if (filled($categories['sanitized'])) {

            $this->storeCategories($categories['sanitized']);
        }

        unset($categories['sanitized']);

        if (count($categories['filtered'])) {
            $columns = array_keys($categories["filtered"][0]);
            unset($columns[array_search("errorBag", $columns)]);
            return array_merge($categories, ["columns" => $columns]);
        }

        return $count;
    }

}