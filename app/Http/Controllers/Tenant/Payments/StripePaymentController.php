<?php

namespace App\Http\Controllers\Tenant\Payments;

use App\Http\Controllers\Controller;
use App\Models\Core\Setting\Setting;
use App\Models\Tenant\Payments\Stripe\ExecuteStripe;
use App\Repositories\Core\Setting\SettingRepository;
use Illuminate\Http\Request;

class StripePaymentController extends Controller
{
    public function handleStripeGet()
    {
        $setting = $this->getStripeCredentials();
        if (!$setting)
            abort(404);

        $stripe = new ExecuteStripe($setting);
        $intent = $stripe->getPaymentIntent();

        $public_key = $setting['public_key'];
        return view('tenant.payment.stripe.index', compact('public_key', 'intent'));
    }

    public function handleStripePost(Request $request)
    {
        return back();
    }

    private function getStripeCredentials()
    {
        $setting = resolve(SettingRepository::class)->formatSettings(
            Setting::query()->where('context', 'stripe')->get(), true
        );
        return $setting;
    }
}
