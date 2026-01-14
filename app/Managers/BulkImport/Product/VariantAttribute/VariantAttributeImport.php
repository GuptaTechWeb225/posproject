<?php


namespace App\Managers\BulkImport\Product\VariantAttribute;


use App\Exceptions\GeneralException;
use App\Helpers\Core\Traits\Memoization;
use App\Managers\BulkImport\BulkImportManager;
use App\Managers\BulkImport\Customer\CustomerImportContract;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Validator;

class VariantAttributeImport implements CustomerImportContract
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

    public function getSanitized($brands)
    {
        return $this->sanitize($brands)['sanitized'];
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
     * @param UploadedFile|array|Collection $brands
     * @return GeneralException|array
     */
    public function sanitize($variantAttributes)
    {
        if ($variantAttributes instanceof UploadedFile) {
            $variantAttributes = $this
                ->setFile($variantAttributes)
                ->read();
        }

        if (is_array($variantAttributes)) {
            $variantAttributes = collect($variantAttributes);
        }

        if (!($variantAttributes instanceof Collection)) {
            return new GeneralException('Type error');
        }

        $filtered = $this->validateBrands($variantAttributes);
        $sanitized = $variantAttributes->except(
            $filtered->keys()
        );

        return [
            'filtered' => $filtered->values(),
            'sanitized' => $sanitized->values(),
            'columns' => $this->columns
        ];
    }

    public function validateBrands($variantAttributes)
    {
        if (!is_array($variantAttributes)) {
            $variantAttributes = collect($variantAttributes);
        }


        $validated = Validator::make($variantAttributes->toArray(), [
            '*.name' => 'required'
        ])
            ->errors()
            ->getMessages();

        return $variantAttributes->map(function ($customer, $k) use ($validated) {

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
