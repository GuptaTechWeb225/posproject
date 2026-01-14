<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Filters\Common\Auth\UserFilter as AppUserFilter;
use App\Filters\Core\UserFilter;
use App\Http\Controllers\Controller;
use App\Models\Core\Auth\User;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Builder;

class TenantUserAPIController extends Controller
{
    public function __construct(UserFilter $filter)
    {
        $this->filter = $filter;
    }

    public function index()
    {
        $status_id = resolve(StatusRepository::class)->userInvited();
        return (new AppUserFilter(
            User::query()
                //app_admin can't switch to other role because it has no branch_or_warehouse_id
                ->where('id', '!=', 1)
                ->where('status_id', '!=', $status_id)
                ->when(request()->has('existing'), function(Builder $builder) {
                    $builder->whereNotIn('id', explode(',', request('existing')));
                })
                ->when(optional(tenant())->is_single, function (Builder $builder) {
                    $builder->whereHas('roles', function (Builder $builder) {
                        $builder->where('tenant_id', tenant()->id);
                    });
                }, function (Builder $builder) {
                    $builder->whereHas('tenants', function (Builder $builder) {
                        $builder->where('id', tenant()->id);
                    });
                })->when(request()->include_profile_picture, function (Builder $builder) {
                    $builder->with('profilePicture');
                })
        ))->filter()
            ->filters($this->filter)
            ->get(['id', 'first_name', 'last_name', 'email']);
    }
}
