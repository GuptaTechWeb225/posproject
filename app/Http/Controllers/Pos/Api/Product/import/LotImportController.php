<?php

namespace App\Http\Controllers\Pos\Api\Product\import;

use App\Http\Controllers\Controller;
use App\Imports\LotImport;
use App\Services\Import\LotImportService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\HeadingRowImport;

class LotImportController extends Controller
{
    public function importStocks(Request $request): \Illuminate\Http\Response|array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $request->validate([
            'import_stock' => 'file|mimes:csv,txt|required',

            //directly from request
            'branch_or_warehouse_id'=> 'required',
            'supplier_id'=> 'required',
            'purchase_date'=> 'required',
            'purchase_status'=> 'required',
            'reference_no'=> 'required|unique:lots',
        ]);

        // get current maximum execution time value
        $current_execution_time = ini_get('max_execution_time');

        // maximum execution time is to set 300s
        ini_set('max_execution_time', 300);

        //get current $memory_limit
        $current_memory_limit = ini_get('memory_limit');

        //set memory limit to 512M
        ini_set('memory_limit', '512M');

        //import file
        //------------------
        $file = $request->file('import_stock');

        //lot info
        //------------------
        $lotData =  $request->except('import_stock');

        //count row number
        $rows = count(array_map('str_getcsv', file($file)));


        throw_if($rows > 501,
            ValidationException::withMessages([
                'import_stock' => [__t('maximum_row_exceeded_message')]
            ])
        );

        //----------------------------------
        //Create / store a new lot
        //----------------------------------
        $createdLotInfo = resolve(LotImportService::class)
            ->setAttrs($lotData)
            ->storeLotData($lotData);

        //----------------------------------
        //store lot variants from uploaded file
        //----------------------------------
        $import = new LotImport($createdLotInfo->id);
        $headings = (new HeadingRowImport)->toArray($file);


        $missingField = array_diff($import->requiredHeading, $headings[0][0]);

        if (count($missingField) > 0) return response(collect($missingField)->values(), 423);

        $import->import($file);
        $failures = $import->failures();

        // set to previous maximum execution time value
        ini_set('max_execution_time', $current_execution_time);
        //set its previous state of memory limit
        ini_set('memory_limit', $current_memory_limit);


        //----------------------------------
        //Update stock
        //----------------------------------
        resolve(LotImportService::class)
            ->updateStock($createdLotInfo->id);

        //partial import
        if ($failures->count() > 0) {
            $stat = import_failed($file, $failures);

            return [
                'status' => 200,
                'message' => trans('default.partially_imported',[
                    'subject' => __t('stocks')
                ]),
                'stat' => $stat,
            ];
        }

        return [
            'status' => 200,
            'message' => trans('default.has_been_imported_successfully',[
                'subject' => __t('stocks')
            ])
        ];
    }


}
