<?php

namespace App\Http\Controllers\Pos\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Setting\InvoiceSettingRequest;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Order\ReturnOrder;
use App\Repositories\Core\Setting\SettingRepository;
use App\Services\Core\Setting\SettingService;

class InvoiceSettingController extends Controller
{
    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function getInvoiceSetting()
    {
        $sales_count = Order::count();
        $returns_count = ReturnOrder::count();
        return [
            ...resolve(SettingRepository::class)->getFormattedSettings('app'),
            'products_sold' => boolval($sales_count),
            'products_returned' => boolval($returns_count)
        ];
    }

    public function store(InvoiceSettingRequest $request)
    {
        // if ($request->type === 'sales'){
        //     $checkIfAlreadyGenerated = Order::count();
        //     if ($checkIfAlreadyGenerated > 0) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => __t('sales_invoice_already_configured')
        //         ]);
        //     }
        // }else{
        //     $checkIfAlreadyGenerated = ReturnOrder::count();
        //     if ($checkIfAlreadyGenerated > 0) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => __t('return_invoice_already_configured')
        //         ]);
        //     }
        // }

        $this->service->update();

        return updated_responses('invoice_setting');

    }
}
