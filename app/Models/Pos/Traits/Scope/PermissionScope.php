<?php

namespace App\Models\Pos\Traits\Scope;

trait PermissionScope
{

    public function scopePermission($query)
    {
        $role = auth()->user()->roles[0];

        if ( $role->is_admin == true || $role->is_manager == true || $role->is_warehouse_manager == true){
            $query->where('id', '!=', null); //all branch warehouse lists
        }else{
            $query->where('id', auth()->user()->branch_or_warehouse_id); //own branch_warehouse
        }
    }
}
