<?php


namespace App\Managers\BulkImport\Product\Category;


use App\Exceptions\GeneralException;
use App\Helpers\Core\Traits\Memoization;
use App\Managers\BulkImport\BulkImportManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Validator;

class CategoryImport implements CategoryImportContract
{
    protected $import;
    protected $file;
    protected $columns;
    use Memoization;

    public function __construct(BulkImportManager $manager)
    {
        $this->import = $manager;
    }

    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        $this->columns = $this->import->fields($file);
        return $this;
    }

    /**
     *
     * @return GeneralException|array
     */
    public function preview()
    {
        return $this->sanitize(
            $this->file
        );
    }

    public function getFiltered()
    {
        return $this->sanitize($this->file)['filtered'];
    }

    public function getSanitized($categories)
    {
        return $this->sanitize($categories)['sanitized'];
    }


    private function writableColumns()
    {
        return [
            'name' => '',
        ];
    }

    /**
     * @return array
     */
    public function read(): array
    {
        $metas = $this->writableColumns();

        return $this->import->readImported($this->file, function ($row) use ($metas) {
            foreach ($this->columns as $key => $field) {
                    $metas[$field] = $row[$key];
            }
            return $metas;
        });
    }

    /**
     * @param UploadedFile|array|Collection $sub_groups
     * @return GeneralException|array
     */
    public function sanitize($sub_groups)
    {
        if ($sub_groups instanceof UploadedFile) {
            $sub_groups = $this
                ->setFile($sub_groups)
                ->read();
        }

        if (is_array($sub_groups)) {
            $sub_groups = collect($sub_groups);
        }

        if (!($sub_groups instanceof Collection)) {
            return new GeneralException('Type error');
        }

        $filtered = $this->validateCategories($sub_groups);
        $sanitized = $sub_groups->except(
            $filtered->keys()
        );

        return [
            'filtered' => $filtered->values(),
            'sanitized' => $sanitized->values(),
            'columns' => $this->columns
        ];
    }

    public function validateCategories($sub_groups)
    {
        if (!is_array($sub_groups)) {
            $sub_groups = collect($sub_groups);
        }


        $validated = Validator::make($sub_groups->toArray() , [
            '*.name' => 'required|max:160',
        ])
            ->errors()
            ->getMessages();

        return $sub_groups->map(function ($sub_group, $k) use ($validated) {

            $t['errorBag'] = [];
            foreach (array_keys($sub_group) as $key) {
                if (in_array("$k.$key", array_keys($validated))) {
                    $t['errorBag'] += [
                        $key => preg_replace( "/\d(.)/", "", $validated["$k.$key"])
                    ];
                }
            }
            return array_merge($sub_group, $t);
        })->filter(function ($sub_group) {
            return !empty($sub_group['errorBag']);
        });
    }


    public function getColumns()
    {
        return $this->columns;
    }
}
