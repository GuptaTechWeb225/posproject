<?php


namespace App\Models\Tenant\Payments\Paypal;


use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class ExecutePayment extends Paypal
{
    public function execute(): Payment
    {
        $payment = $this->getPayment();
        $execution = $this->createExecution();

        $execution->addTransaction($this->transaction());
        $result = $payment->execute($execution, $this->apiContext);

        return $result;
    }

    /**
     * @return Payment
     */
    protected function getPayment(): Payment
    {
        $paymentId = \request('paymentId');
        $payment = Payment::get($paymentId, $this->apiContext);
        return $payment;
    }

    /**
     * @return PaymentExecution
     */
    protected function createExecution(): PaymentExecution
    {
        $execution = new PaymentExecution();
        $execution->setPayerId(\request('PayerID'));
        return $execution;
    }


    /**
     * @return Transaction
     */
    protected function transaction(): Transaction
    {
        $transaction = new Transaction();
        $transaction->setAmount($this->amount());
        return $transaction;
    }
}