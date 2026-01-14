<?php


namespace App\Managers\BulkImport\Supplier;


use App\Exceptions\GeneralException;
use App\Helpers\Core\Traits\Memoization;
use App\Managers\BulkImport\BulkImportManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Validator;

class SupplierImporter implements SupplierImportContract
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

    public function getSanitized($suppliers)
    {
        return $this->sanitize($suppliers)['sanitized'];
    }


    private function writableColumns()
    {
        return [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'phone_number' => '',
            'address' => '',
            'company' => '',
        ];
    }

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
     * @param UploadedFile|array|Collection $suppliers
     * @return GeneralException|array
     */
    public function sanitize($suppliers)
    {
        if ($suppliers instanceof UploadedFile) {
            $suppliers = $this
                ->setFile($suppliers)
                ->read();
        }

        if (is_array($suppliers)) {
            $suppliers = collect($suppliers);
        }

        if (!($suppliers instanceof Collection)) {
            return new GeneralException('Type error');
        }

        $filtered = $this->validateSuppliers($suppliers);
        $sanitized = $suppliers->except(
            $filtered->keys()
        );

        return [
            'filtered' => $filtered->values(),
            'sanitized' => $sanitized->values(),
            'columns' => $this->columns
        ];
    }

    private function validateSuppliers($suppliers)
    {
        if (!is_array($suppliers)) {
            $suppliers = collect($suppliers);
        }

        $validated = Validator::make($suppliers->toArray(), [
            '*.first_name' => 'required',
            '*.email' => 'required|email|unique:suppliers,email',
        ])
            ->errors()
            ->getMessages();

        return $suppliers->map(function ($supplier, $k) use ($validated) {

            $t['errorBag'] = [];
            foreach (array_keys($supplier) as $key) {
                if (in_array("$k.$key", array_keys($validated))) {
                    $t['errorBag'] += [
                        $key => preg_replace( "/\d(.)/", "", $validated["$k.$key"])
                    ];
                }
            }

            return array_merge($supplier, $t);
        })->filter(function ($supplier) {
            return !empty($supplier['errorBag']);
        });
    }

    public function getColumns()
    {
        return $this->columns;
    }
}