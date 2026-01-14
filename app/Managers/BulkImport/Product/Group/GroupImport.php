<?php


namespace App\Managers\BulkImport\Product\Group;


use App\Exceptions\GeneralException;
use App\Helpers\Core\Traits\Memoization;
use App\Managers\BulkImport\BulkImportManager;
use App\Managers\BulkImport\Customer\CustomerImportContract;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Validator;

class GroupImport implements CustomerImportContract
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

    public function getSanitized($productGroups)
    {
        return $this->sanitize($productGroups)['sanitized'];
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
     * @param UploadedFile|array|Collection $productGroups
     * @return GeneralException|array
     */
    public function sanitize($productGroups)
    {
        if ($productGroups instanceof UploadedFile) {
            $productGroups = $this
                ->setFile($productGroups)
                ->read();
        }

        if (is_array($productGroups)) {
            $productGroups = collect($productGroups);
        }

        if (!($productGroups instanceof Collection)) {
            return new GeneralException('Type error');
        }

        $filtered = $this->validateProductGroups($productGroups);
        $sanitized = $productGroups->except(
            $filtered->keys()
        );

        return [
            'filtered' => $filtered->values(),
            'sanitized' => $sanitized->values(),
            'columns' => $this->columns
        ];
    }

    public function validateProductGroups($productGroups)
    {
        if (!is_array($productGroups)) {
            $productGroups = collect($productGroups);
        }


        $validated = Validator::make($productGroups->toArray(), [
            '*.name' => 'required'
        ])
            ->errors()
            ->getMessages();

        return $productGroups->map(function ($customer, $k) use ($validated) {

            $t['errorBag'] = [];
            foreach (array_keys($customer) as $key) {
                if (in_array("$k.$key", array_keys($validated))) {
                    $t['errorBag'] += [
                        $key => preg_replace("/\d(.)/", "", $validated["$k.$key"])
                    ];
                }
            }
            return array_merge($customer, $t);
        })->filter(function ($customer) {
            return !empty($customer['errorBag']);
        });
    }


    public function getColumns()
    {
        return $this->columns;
    }
}
