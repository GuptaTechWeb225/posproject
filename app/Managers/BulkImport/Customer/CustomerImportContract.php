<?php


namespace App\Managers\BulkImport\Customer;


interface CustomerImportContract
{
    public function read() : array;

    public function sanitize($subscribers);
}
