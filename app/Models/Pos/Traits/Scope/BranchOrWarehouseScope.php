<?php

namespace App\Models\Pos\Traits\Scope;

trait BranchOrWarehouseScope
{

    public function scopeBranchOrWarehouse($query, $branch_or_warehouse_id = null)
    {
        $role = auth()->user()->roles[0]; // for single role

        if ($role->is_admin == true || $role->is_manager == true || $role->is_warehouse_manager == true) {
            $branch_or_warehouse_id != "null" ? $query->where('branch_or_warehouse_id', $branch_or_warehouse_id) : '';
        } else {
            $query->where('branch_or_warehouse_id', auth()->user()->branch_or_warehouse_id); //
        }
    }
}
