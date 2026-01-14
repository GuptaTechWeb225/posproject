<?php


namespace App\Models\Tenant\Rules;


trait CouponRules
{
    public function createdRules()
    {
        return [
            'name' => 'required|max:160',
            'start_at' => 'required|date_format:Y-m-d H:i:s',
            'end_at' => 'required|date_format:Y-m-d H:i:s',
            'code' => 'required|max:100|unique:coupons,code',
            'discount' => 'required_if:discount_id,null',
            'discount_id' => 'required_if:discount,null|exists:discounts,id'
        ];
    }

    public function updatedRules()
    {
        return [
            'name' => 'required|max:160',
            'start_at' => 'required|date_format:Y-m-d H:i:s',
            'end_at' => 'required|date_format:Y-m-d H:i:s',
            'code' => 'required|max:100|unique:coupons,code,' . request('id'),
            'discount' => 'required_if:discount_id,null',
            'discount_id' => 'required_if:discount,null|exists:discounts,id'

        ];
    }

}