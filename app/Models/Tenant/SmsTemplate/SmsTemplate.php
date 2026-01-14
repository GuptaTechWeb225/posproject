<?php

namespace App\Models\Tenant\SmsTemplate;

use App\Models\Tenant\Rules\SmsTemplateRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsTemplate extends TenantModel
{
    use HasFactory, SmsTemplateRules;

    protected $fillable = [
        'subject','content', 'is_default', 'tenant_id'
    ];
}
