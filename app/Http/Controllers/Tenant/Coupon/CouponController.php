<?php

namespace App\Http\Controllers\Tenant\Coupon;

use App\Filters\Tenant\CouponFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Coupon\CouponRequest;
use App\Models\Tenant\Discount\Coupon;
use App\Services\Tenant\Coupon\CouponServices;
use DB;

class CouponController extends Controller
{
    public function __construct(CouponServices $services, CouponFilter $filter)
    {
        $this->service = $services;
        $this->filter  = $filter;
    }


    public function index(): object
    {
        return $this->service
            ->filters($this->filter)
            ->orderByDesc('id')
            ->select(['id', 'name', 'start_at', 'end_at', 'code', 'discount'])
            ->paginate(
                request('per_page', 10)
            );
    }


    public function store(CouponRequest $request): array
    {
        $coupon = DB::transaction(function () use ($request) {

            $this->service
                ->setAttrs($request->only('name', 'start_at', 'end_at', 'code', 'discount'))
                ->save();

            $this->service
                ->discounts()
                ->sync($request->get('discount_id'));

            return $this->service;
        });

        return created_responses('coupon', [
            'coupon' => $coupon
        ]);
    }


    public function show(Coupon $coupon): object
    {
        return $coupon->load('discounts');
    }


    public function update(Coupon $coupon, CouponRequest $request): array
    {

        $coupon = DB::transaction(function () use($request, $coupon){

            $this->service
                ->setModel($coupon)
                ->save($request->only('name', 'start_at', 'end_at', 'code', 'discount'));
            $coupon
                ->discounts()
                ->sync($request->get('discount_id'));

        });

        return updated_responses('coupon', [
            'coupon' => $coupon
        ]);
    }


    public function destroy(Coupon $coupon): array
    {

        DB::transaction(function () use ($coupon){

            $coupon
                ->discounts()
                ->detach();

            $coupon->delete();

        });

        return deleted_responses('coupon');
    }
}
