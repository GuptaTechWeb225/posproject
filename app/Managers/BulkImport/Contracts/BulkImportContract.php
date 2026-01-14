<?php


namespace App\Managers\BulkImport\Contracts;


use Illuminate\Http\UploadedFile;

interface BulkImportContract
{
    public function readImported(UploadedFile $file, \Closure $callback) : array ;
}
