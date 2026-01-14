<?php


namespace App\Managers\BulkImport\Supplier;


interface SupplierImportContract
{
    public function read() : array;

    public function sanitize($subscribers);
}
