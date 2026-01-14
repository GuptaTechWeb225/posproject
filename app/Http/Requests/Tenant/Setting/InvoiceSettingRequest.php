<?php

namespace App\Http\Requests\Tenant\Setting;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        switch (request()->get('type')) {
            case 'sales':
                return [
                    'sales_invoice_prefix' => 'required|string',
                    'sales_invoice_suffix' => 'nullable|string',
                    'sales_invoice_starts_from' => 'required|int',
                    'sales_invoice_logo' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:2048',
                    'sales_invoice_auto_generate' => 'required|boolean',
                    'sales_invoice_auto_email' => 'required|boolean',
                ];
                break;
            case 'return' :
                return [
                    'return_invoice_prefix' => 'required|string',
                    'return_invoice_suffix' => 'nullable|string',
                    'return_invoice_starts_from' => 'required|int',
                    'return_invoice_logo' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:2048',
                    'return_invoice_auto_generate' => 'required|boolean',
                    'return_invoice_auto_email' => 'required|boolean',
                ];
                break;
        }
    }

    public function attributes()
    {
        switch (request()->get('type')) {
            case 'sales':
                return [
                    'sales_invoice_prefix' => 'invoice prefix',
                    'sales_invoice_suffix' => 'invoice suffix',
                    'sales_invoice_starts_from' => 'invoice starts from',
                    'sales_invoice_logo' => 'invoice logo',
                    'sales_invoice_auto_generate' => 'invoice auto generate',
                    'sales_invoice_auto_email' => 'invoice auto email',
                ];
                break;
            case 'return':
                return [
                    'return_invoice_prefix' => 'invoice prefix',
                    'return_invoice_suffix' => 'invoice suffix',
                    'return_invoice_starts_from' => 'invoice starts from',
                    'return_invoice_logo' => 'invoice logo',
                    'return_invoice_auto_generate' => 'invoice auto generate',
                    'return_invoice_auto_email' => 'invoice auto email',
                ];
                break;
        }
    }
}
