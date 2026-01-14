<?php

namespace App\Models\Pos\Product\Tax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BranchTax extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'branch_id', 'tax_id', 'start_date', 'end_date'
    ];
}
