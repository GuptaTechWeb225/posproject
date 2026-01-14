<?php


namespace App\Models\Tenant\Payments\Paypal;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class Paypal
{
    public $apiContext;
    public function __construct($setting)
    {
        $client_id = decrypt($setting['client_id']);
        $secret_key = decrypt($setting['secret_key']);
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential($client_id,$secret_key)
        );
    }

    /**
     * @return Details
     */
    protected function details(): Details
    {
        $details = new Details();
        $details->setShipping(1.2)
            ->setTax(1.3)
            ->setSubtotal(7.50);
        return $details;
    }

    /**
     * @param Details $details
     * @return Amount
     */
    protected function amount(): Amount
    {
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal(10)
            ->setDetails($this->details());
        return $amount;
    }
}