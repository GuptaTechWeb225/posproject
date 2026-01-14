<?php

namespace App\Http\Controllers\Pos\Api\Product\import;

use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\HeadingRowImport;

class ProductImportController extends Controller
{
    public function importProducts(Request $request): \Illuminate\Http\Response|array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $request->validate([
            'import_file' => 'file|mimes:csv,txt|required'
        ]);

        // get current maximum execution time value
        $current_execution_time = ini_get('max_execution_time');

        // maximum execution time is to set 300s
        ini_set('max_execution_time', 300);

        //get current $memory_limit
        $current_memory_limit = ini_get('memory_limit');

        //set memory limit to 512M
        ini_set('memory_limit', '512M');

        $file = $request->file('import_file');
        //count row number
        $rows = count(array_map('str_getcsv', file($file)));

        throw_if($rows > 501,
            ValidationException::withMessages([
                'import_file' => [__t('maximum_row_exceeded_message')]
            ])
        );

        $import = new ProductsImport();
        $headings = (new HeadingRowImport)->toArray($file);

        $missingField = array_diff($import->requiredHeading, $headings[0][0]);

        if (count($missingField) > 0) {
            return response(collect($missingField)->values(), 423);
        }

        $import->import($file);
        $failures = $import->failures();

        // set to previous maximum execution time value
        ini_set('max_execution_time', $current_execution_time);
        //set its previous state of memory limit
        ini_set('memory_limit', $current_memory_limit);


        //partial import
        if ($failures->count() > 0) {
            $stat = import_failed($file, $failures);

            //store all failed data...

            return [
                'status' => 200,
                'message' => trans('default.partially_imported',[
                    'subject' => __t('products')
                ]),
                'stat' => $stat,
                'failures' => $failures[0]->values(),
            ];
        }

        return [
            'status' => 200,
            'message' => trans('default.has_been_imported_successfully',[
                'subject' => __t('products')
            ])
        ];
    }










}
