<?php


namespace App\Models\Tenant\Rules;


trait PaymentMethodRules
{
    public function createdRules()
    {
        return [
            'name' => 'required',
            'alias' => 'required|in:cash,credit',
            'type' => 'required',
            'rounded_to' => 'required',
            'is_default' => 'required',
            'is_for_client' => 'required',
            'client_id' => 'required_if:type,==,paypal',
            'mode' => 'required_if:type,==,paypal',
            'public_key' => 'required_if:type,==,stripe',
            'secret_key' => 'required_if:type,==,paypal,stripe',

        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}
