<?php


namespace App\Managers\BulkImport\Customer;


use App\Exceptions\GeneralException;
use App\Helpers\Core\Traits\Memoization;
use App\Managers\BulkImport\BulkImportManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Validator;

class CustomerImport implements CustomerImportContract
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

    public function getSanitized($customers)
    {
        return $this->sanitize($customers)['sanitized'];
    }


    private function writableColumns()
    {
        return [
            'first_name' => '',
            'last_name' => '',
            'tin' => '',
            'customer_group' => '',
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
                if ($field == 'customer_group') {
                    $metas[$field] = strtolower($row[$key]);
                } else {
                    $metas[$field] = $row[$key];
                }
            }
            return $metas;
        });
    }

    /**
     * @param UploadedFile|array|Collection $customers
     * @return GeneralException|array
     */
    public function sanitize($customers)
    {
        if ($customers instanceof UploadedFile) {
            $customers = $this
                ->setFile($customers)
                ->read();
        }

        if (is_array($customers)) {
            $customers = collect($customers);
        }

        if (!($customers instanceof Collection)) {
            return new GeneralException('Type error');
        }

        $filtered = $this->validateCustomers($customers);
        $sanitized = $customers->except(
            $filtered->keys()
        );

        return [
            'filtered' => $filtered->values(),
            'sanitized' => $sanitized->values(),
            'columns' => $this->columns
        ];
    }

    public function validateCustomers($customers)
    {
        if (!is_array($customers)) {
            $customers = collect($customers);
        }


        $validated = Validator::make($customers->toArray() , [
            '*.first_name' => 'required',
            '*.tin' => 'required|distinct',
            '*.customer_group' => "required"
        ])
            ->errors()
            ->getMessages();

        return $customers->map(function ($customer, $k) use ($validated) {

            $t['errorBag'] = [];
            foreach (array_keys($customer) as $key) {
                if (in_array("$k.$key", array_keys($validated))) {
                    $t['errorBag'] += [
                        $key => preg_replace( "/\d(.)/", "", $validated["$k.$key"])
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
