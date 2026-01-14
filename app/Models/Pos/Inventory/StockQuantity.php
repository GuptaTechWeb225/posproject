<?php

namespace App\Models\Pos\Inventory;

use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockQuantity extends TenantModel
{
    use HasFactory;

    protected $fillable = ['stock_id', 'adjustment_type_id', 'quantity'];

    protected $casts = [
        'stock_id' => 'integer',
        'adjustment_type_id' => 'integer',
    ];
}
