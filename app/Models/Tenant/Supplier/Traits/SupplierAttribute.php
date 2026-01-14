<?php
namespace App\Models\Tenant\Supplier\Traits;


trait SupplierAttribute
{
    public function getFullNameAttribute()
    {
        return $this->last_name
            ? $this->first_name.' '.$this->last_name
            : $this->first_name;
    }

    public function getNameAttribute()
    {
        return $this->full_name;
    }
}
