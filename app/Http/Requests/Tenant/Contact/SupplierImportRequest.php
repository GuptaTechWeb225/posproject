<?php

namespace App\Http\Requests\Tenant\Contact;

use Illuminate\Foundation\Http\FormRequest;

class SupplierImportRequest extends FormRequest
{
    public function rules()
    {
        return [
            'suppliers' => 'required|mimes:csv,txt'
        ];
    }
}
