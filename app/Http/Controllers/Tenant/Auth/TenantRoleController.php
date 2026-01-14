<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Filters\Core\RoleFilter;
use App\Http\Controllers\Core\Auth\Role\RoleController;
use App\Http\Requests\Core\Auth\Role\RoleRequest;
use App\Models\Core\Auth\Role;
use App\Services\Core\Auth\RoleService;

class TenantRoleController extends RoleController
{
    public function __construct(RoleService $roleService, RoleFilter $filter)
    {
        $this->service = $roleService;
        $this->filter = $filter;
        parent::__construct($roleService, $filter);
    }

    public function store(RoleRequest $request)
    {
        $this->service->save(
            array_merge($request->except('is_admin'), ['is_default' => $request->get('is_manager')])
        );

        $this->service->notify('roles_created')
            ->when(count($request->get('permissions', [])), function (RoleService $service) use ($request) {
                $service->assignPermissions($request->get('permissions'));
            });

        return created_responses('role');
    }

    public function update(Role $role, RoleRequest $request)
    {
        $this->service->setModel($role)
            ->setAttributes(array_merge($request->except('is_admin'), ['is_default' => $request->get('is_manager')]))
            ->update();

        return updated_responses('role');
    }
}
