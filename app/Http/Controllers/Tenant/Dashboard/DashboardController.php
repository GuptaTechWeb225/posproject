<?php

namespace App\Http\Controllers\Tenant\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Tenant\Dashboard\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function highlights(Request $request)
    {
        return $this->service
            ->setAttributes($request->all())
            ->highlightsInfo();
    }

    public function recentSales(Request $request)
    {
        return $this->service
            ->setAttributes($request->all())
            ->recentSales();
    }

    public function topSellingProducts(Request $request)
    {
        return $this->service
            ->setAttributes($request->all())
            ->topSellingProducts();
    }

    public function topCustomers(Request $request)
    {
        return $this->service
            ->setAttributes($request->all())
            ->topCustomers();
    }

    public function stockSummary(Request $request)
    {
        return $this->service
            ->setAttributes($request->all())
            ->stockSummary();
    }

    public function purchaseSaleStatistics(Request $request): array
    {
        return $this->service
            ->setAttributes($request->all())
            ->purchaseSaleStatistics();
    }
}
