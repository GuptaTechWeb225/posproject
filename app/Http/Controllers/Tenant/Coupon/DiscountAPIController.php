<?php

namespace App\Http\Controllers\Tenant\Coupon;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Discount\Discount;

class DiscountAPIController extends Controller
{
    public function index()
    {
        return Discount::query()->get(['name', 'id']);
    }
}
