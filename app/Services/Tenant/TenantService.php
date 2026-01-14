<?php

namespace App\Services\Tenant;

use App\Services\Core\BaseService;

/**
 * @method \Illuminate\Database\Eloquent\Builder newModelQuery()
 * @method \Illuminate\Database\Eloquent\Builder newQuery()
 * @method \Illuminate\Database\Eloquent\Builder query()
 * @method \Illuminate\Database\Eloquent\Builder whereCreatedAt($value)
 * @method \Illuminate\Database\Eloquent\Builder whereId($value)
 * @method \Illuminate\Database\Eloquent\Builder wherePassword($value)
 * @method \Illuminate\Database\Eloquent\Builder whereUpdatedAt($value)
 * @method \Illuminate\Database\Eloquent\Builder whereUserId($value)
 * @method \Illuminate\Database\Eloquent\Builder filters(\App\Filters\FilterBuilder $filter)
 * @method \Illuminate\Database\Eloquent\Builder with($relation)
 * @method \Illuminate\Database\Eloquent\Builder orderByDesc($id)
 * @method \Illuminate\Database\Eloquent\Builder paginate($paginate)
 * @method \Illuminate\Database\Eloquent\Builder latest($paginate)
 * @method \Illuminate\Database\Eloquent\Builder addSelect($paginate)
 *
 */
class TenantService extends BaseService
{
    public function delete(): self
    {
        $this->model->delete();

        return $this;
    }

    public function checkAndResetDefault($model, $value): self
    {
        if ($value) {
            $model->query()->update(['is_default' => 0]);

            return $this;
        }

        return $this;
    }
}
