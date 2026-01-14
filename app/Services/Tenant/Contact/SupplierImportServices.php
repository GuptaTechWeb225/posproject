<?php

namespace App\Services\Tenant\Contact;

use App\Managers\BulkImport\Supplier\SupplierImporter;
use App\Models\Tenant\Supplier\Supplier;
use App\Services\Core\BaseService;
use DB;
use Illuminate\Support\Arr;

class SupplierImportServices extends BaseService
{
    private SupplierImporter $importer;

    public function __construct(Supplier $supplier)
    {
        $this->model = $supplier;
        $this->importer = resolve(SupplierImporter::class);
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
        $suppliers = $this->getAttr('suppliers');

        DB::transaction(function () use ($suppliers) {

            foreach ($suppliers as $key => $supplier) {

                $createdSupplierId = $this->storeSuppliers($supplier);

                $this->storeSupplierContact($supplier, $createdSupplierId);

            }

        });
    }


    private function storeSuppliers($supplier): int
    {
        $createdSupplier = $this->model
            ->create([
                "first_name" => $supplier['first_name'],
                "last_name" => $supplier['last_name'],
                "email" => $supplier['email'],
            ]);

        return $createdSupplier->id;
    }


    private function storeSupplierContact($supplier, $createdSupplierId): bool
    {
        $contacts = Arr::only($supplier, ['phone_number', 'address', 'company']);

        $data = [];

        foreach ($contacts as $sKey => $value) {

            if ($value) {

                $data[] = [
                    'contactable_id' => $createdSupplierId,
                    'contactable_type' => Supplier::class,
                    'name' => $sKey,
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        $this->model
            ->contacts()
            ->insert($data);

        return true;
    }

    public function store()
    {
        $suppliers = $this->importer->sanitize(
            $this->getAttr('suppliers')
        );

        $count = 0;

        if (filled($suppliers['sanitized'])) {

            DB::transaction(function () use ($suppliers) {

                foreach ($suppliers['sanitized'] as $key => $supplier) {

                    $createdSupplierId = $this->storeSuppliers($supplier);

                    $this->storeSupplierContact($supplier, $createdSupplierId);
                }
            });
        }

        unset($suppliers['sanitized']);

        if (count($suppliers['filtered'])) {
            $columns = array_keys($suppliers["filtered"][0]);
            unset($columns[array_search("errorBag", $columns)]);
            return array_merge($suppliers, ["columns" => $columns]);
        }

        return $count;
    }
}