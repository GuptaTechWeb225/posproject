<?php

namespace App\Models\Tenant\Rules;

trait SupplierRules
{
    public function createdRules(): array
    {
        return [
          'name' => 'required|string|max:140',
          'supplier_no' => 'required|unique:suppliers,supplier_no',
        ];
    }

    public function updatedRules(): array
    {
        return [
            'name' => 'required|max:140',
            'supplier_no' => 'required|max:140|unique:suppliers,supplier_no,'.request('supplier')->id
        ];
    }
}
