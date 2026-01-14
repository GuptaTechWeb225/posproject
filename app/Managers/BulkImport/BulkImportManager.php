<?php


namespace App\Managers\BulkImport;


use App\Managers\BulkImport\Contracts\BulkImportContract;
use Illuminate\Http\UploadedFile;

class BulkImportManager implements BulkImportContract
{
    /**
     * @param UploadedFile|null $file
     * @param \Closure $callback
     * @return array
     */
    public function readImported(?UploadedFile $file, \Closure $callback) : array
    {
        return $this->readCSV($file)
            ->except(0)
            ->map(function ($row, $key) use($callback) {

            return $callback($row, $key);

        })->values()
            ->toArray();
    }

    private function readCSV($file)
    {
        return collect((array_map('str_getcsv', file($file))));
    }

    public function fields($file)
    {
        return $this->readCSV($file)->first();
    }
}
