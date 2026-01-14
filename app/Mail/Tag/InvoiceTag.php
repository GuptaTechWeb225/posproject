<?php

namespace App\Mail\Tag;

use App\Models\Core\Setting\Setting;

class InvoiceTag extends Tag
{
    protected $order;

    public function __construct($order, $notifier = null, $receiver = null)
    {
        $this->order = $order;
        $this->notifier = $notifier;
        $this->receiver = $receiver;
        $this->resourceurl = config('notification.user_front_end_route_name');
    }

    public function invoiceContent()
    {
        $invoiceLogo = Setting::query()->where('name', 'sales_invoice_logo')->first();
        return [
            '{receiver_name}' => $this->order->customer->full_name,
            '{invoice_number}' => $this->order->invoice_number,
            '{invoice_date}' => $this->order->ordered_at,
            '{name}' => $this->order->customer->full_name,
            '{logo_source}' => $invoiceLogo->value ?? null,
            '{company_name}' => settings('company_name'),
        ];
    }

    public function notification()
    {
        return [
            '{name}' => $this->order->customer->full_name,
            '{logo}' => asset(settings('company_logo')),
            '{company_name}' => settings('company_name'),
        ];
    }

    public function invoiceSendSubject()
    {
        $logo = settings('company_logo');
        return [
            '{invoice_number}' => $this->order->invoice_number ?? null,
            '{invoice_date}' =>  $this->order->ordered_at->format('Y-m-d') ?? '',
            '{company_name}' => settings('company_name'),
            '{app_logo}' => asset(empty($logo) ? '/images/logo.png' : $logo)
        ];
    }
}


