<?php

namespace App\Http\Controllers\Pos\Api\Product\Products;

use App\Exceptions\GeneralException;
use App\Filters\Tenant\GroupFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Product\GroupRequest;
use App\Models\Pos\Product\Group\Group;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\Product\GroupServices;

class GroupController extends Controller
{
    public function __construct(GroupServices $services, GroupFilter $filter)
    {
        $this->service = $services;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->with('createdBy:id,first_name,last_name', 'products:id')
            ->withCount('products')
            ->orderByDesc('id')
            ->paginate(
                request('per_page', 10)
            );
    }

    public function store(GroupRequest $request)
    {
        $status = resolve(StatusRepository::class)->groupActive();
        $attributes = array_merge($request->only('name', 'description'), [
            'status_id' => $status
        ]);

        $this->service
            ->saveGroup($attributes);

        return created_responses('group');
    }

    public function show(Group $group): Group
    {
        return $group->load('createdBy:id,first_name,last_name', 'products:id');
    }

    public function update(GroupRequest $request, Group $group)
    {
        $this->service
            ->setModel($group)
            ->saveGroup($request->only('name', 'description'));

        return updated_responses('group');
    }

    public function destroy(Group $group)
    {
        $group_products = $group->products()->count();
        throw_if($group_products > 0, new GeneralException(__t('the_group_is_in_use')));

        $group->delete();
        return deleted_responses('group');
    }
}
