<?php

namespace App\Models\Tenant\Discount;

use App\Models\Core\Traits\StatusRelationship;
use App\Models\Pos\Traits\Scope\BranchOrWarehouseScope;
use App\Models\Tenant\Discount\Relationship\DiscountRelationship;
use App\Models\Tenant\TenantModel;
use App\Models\Tenant\Traits\CommentRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends TenantModel
{
    use HasFactory, DiscountRelationship, CommentRelationship, StatusRelationship, BranchOrWarehouseScope;

    protected $fillable = [
        'branch_or_warehouse_id', 'discount_type', 'name', 'type', 'value', 'start_at', 'end_at', 'created_by', 'published_by', 'updated_by', 'note', 'status_id', 'discount_id'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->created_by = auth()->id();
        });

        static::updating(function (Model $model) {
            $model->updated_by = auth()->id();
        });
    }
}
