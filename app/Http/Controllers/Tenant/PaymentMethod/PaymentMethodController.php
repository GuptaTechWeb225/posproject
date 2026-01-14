<?php

namespace App\Http\Controllers\Tenant\PaymentMethod;

use App\Filters\Tenant\PaymentMethodFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\PaymentMethod\PaymentMethodRequest;
use App\Models\Tenant\PaymentMethod\PaymentMethod;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\PaymentMethod\PaymentMethodService;
use Illuminate\Support\Facades\DB;

class PaymentMethodController extends Controller
{
    public function __construct(PaymentMethodService $service, PaymentMethodFilter $filter)
    {
        $this->filter = $filter;
        $this->service = $service;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->latest()
            ->with(['status', 'createdBy'])
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    public function store(PaymentMethodRequest $request)
    {
        $paymentMethod = DB::transaction(function () use ($request) {
            $status = resolve(StatusRepository::class)->payment_methodActive();
            $attributes = array_merge($request->only('name', 'type', 'is_default', 'is_for_client', 'rounded_to', 'alias'), [
                'status_id' => $status
            ]);
            $paymentMethod = $this->service
                ->checkAndResetDefault(new PaymentMethod(), $request->is_default)
                ->setAttributes($attributes)
                ->save();

            $this->service
                ->setModel($paymentMethod)
                ->setAttributes($request->only('public_key', 'client_id', 'secret_key', 'mode'))
                ->saveSettings($request->type);

            return $paymentMethod;
        });
        return created_responses('payment_method', ['payment_method' => $paymentMethod]);
    }

    public function show(PaymentMethod $paymentMethod)
    {
        return $this->service
            ->getDataWithFormattedSetting($paymentMethod);
    }

    public function update(PaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        DB::transaction(function () use ($request, $paymentMethod) {
            if ($request->is_default == 1 && $paymentMethod->is_default == 0){
                $this->service
                    ->checkAndResetDefault(new PaymentMethod(), $request->is_default);
            }
            $this->service
                ->setModel($paymentMethod)
                ->save($request->only('name', 'type', 'is_default', 'is_for_client', 'rounded_to', 'status_id'));

            $this->service
                ->setModel($paymentMethod)
                ->setAttributes($request->only('public_key', 'client_id', 'secret_key', 'mode'))
                ->updateSettings();
        });
        return updated_responses('payment_method', ['payment_method' => $paymentMethod]);
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->settings()->delete();
        $paymentMethod->delete();

        return deleted_responses('payment_method');
    }

    public function paymentMethodStatus()
    {
        return $this->service
            ->getPaymentMethodStatus();
    }
}
