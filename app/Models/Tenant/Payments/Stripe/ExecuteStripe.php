<?php


namespace App\Models\Tenant\Payments\Stripe;


use Stripe\PaymentIntent;
use Stripe\Stripe;

class ExecuteStripe
{
    public function __construct($setting)
    {
        Stripe::setApiKey($setting['secret_key']);
    }

    public function getPaymentIntent()
    {
        $payment_intent = PaymentIntent::create([
            'description' => 'Stripe Test Payment',
            'amount' => $this->amount(),
            'currency' => 'USD',
            'payment_method_types' => ['card'],
        ]);
        return $intent = $payment_intent->client_secret;
    }

    private function amount()
    {
        $amount = 100;
        $amount *= 100;
        return $amount = (int)$amount;
    }
}