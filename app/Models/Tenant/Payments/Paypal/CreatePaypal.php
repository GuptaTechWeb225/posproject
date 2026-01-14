<?php


namespace App\Models\Tenant\Payments\Paypal;


use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class CreatePaypal extends Paypal
{
    public function create(): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $payment = $this->payment();
        $payment->create($this->apiContext);
        return redirect($approvalUrl = $payment->getApprovalLink());
    }

    /**
     * @return Payer
     */
    protected function payer(): Payer
    {
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        return $payer;
    }

    /**
     * @param $itemList
     * @return Transaction
     */
    protected function transaction(): Transaction
    {
        $transaction = new Transaction();
        $transaction->setAmount($this->amount())
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());
        return $transaction;
    }

    /**
     * @return RedirectUrls
     */
    protected function redirectUrls(): RedirectUrls
    {
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('support.paypal.execute'))
            ->setCancelUrl(route('support.paypal'));
        return $redirectUrls;
    }

    /**
     * @param $itemList
     * @return Payment
     */
    protected function payment(): Payment
    {
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($this->payer())
            ->setRedirectUrls($this->redirectUrls())
            ->setTransactions(array($this->transaction()));
        return $payment;
    }
}