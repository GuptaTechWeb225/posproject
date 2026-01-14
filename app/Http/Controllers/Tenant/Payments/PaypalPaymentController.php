<?php

namespace App\Http\Controllers\Tenant\Payments;

use App\Http\Controllers\Controller;
use App\Models\Core\Setting\Setting;
use App\Models\Tenant\Payments\Paypal\CreatePaypal;
use App\Models\Tenant\Payments\Paypal\ExecutePayment;
use App\Repositories\Core\Setting\SettingRepository;

class PaypalPaymentController extends Controller
{
    public function handlePaypalGet()
    {
        if (!$this->getPaypalCredentials())
            abort(404);

        return view('tenant.payment.paypal.index');
    }

    public function handlePaypalAction()
    {
        if (!$this->getPaypalCredentials())
            abort(404);

        $payment = new CreatePaypal($this->getPaypalCredentials());
        return $payment->create();
    }

    public function paypalExecute()
    {
        if (!$this->getPaypalCredentials())
            abort(404);

        $payment = new ExecutePayment($this->getPaypalCredentials());
        return $payment->execute();
    }

    private function getPaypalCredentials()
    {
        return $setting = resolve(SettingRepository::class)->formatSettings(
            Setting::query()->where('context', 'paypal')->get()
        );
    }
}
