<?php


namespace App\Managers\BulkImport\Product\Category;


interface CategoryImportContract
{
    public function read() : array;

    public function sanitize($categories);
}
