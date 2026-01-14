<?php

namespace App\Models\Tenant\Rules;

trait CustomerRules
{
    public function createdRules()
    {
        return [
            'first_name' => 'required|string|max:140',
            'tenant_id' => 'required',
            'customer_group_id' => 'required|exists:customer_groups,id',
            'phone_number_details.*.phone_number' => 'required',
            'email_details.*.email' => 'required|email|unique:customers,email',
        ];
    }

    public function updatedRules()
    {
        return [
            'first_name' => 'required|max:140',
            'tenant_id' => 'required',
            'customer_group_id' => 'required|exists:customer_groups,id',
            'phone_number_details.*.phone_number' => 'required',
            'email_details.*.email' => 'required|email|unique:customers,email,'.request()->get('id'),
        ];
    }

    public function message()
    {
        return [
            'email_details.*.email.email' => __t('email_must_be_a_valid_email_address'),
            'address_information_details.*.name.required' => __t('title_field_is_required'),
            'address_information_details.*.details.required' => __t('details_field_is_required'),
            'phone_number_details.*.phone_number.required' => __t('phone_number_field_is_required'),
            'email_details.*.email.required' => __t('email_field_is_required'),
            'email_details.*.email.unique' => __t('email_has_taken'),
        ];
    }
}