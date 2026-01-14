<?php

namespace App\Models\Tenant\InvoiceTemplate;

use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Tenant\InvoiceTemplate\Relationship\CounterRelationship;
use App\Models\Tenant\Rules\InvoiceTemplateRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceTemplate extends TenantModel
{

    use HasFactory, InvoiceTemplateRules, CounterRelationship, BootTrait, CreatedByRelationship;

    protected $fillable = [
        'name', 'default_content', 'custom_content', 'type', 'is_default', 'created_by'
    ];

}
