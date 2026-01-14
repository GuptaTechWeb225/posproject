<?php

namespace App\Models\Pos\Product\Tax;

use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxTax extends TenantModel
{
    use HasFactory,BootTrait, CreatedByRelationship;

    protected $fillable = [
        'parent_id','tax_id','created_by','tenant_id',
    ];

    protected $table = 'tax_tax';

    public function tax(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tax::class, );
    }

    public function parentTax(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tax::class, 'parent_id', 'id');
    }


}
