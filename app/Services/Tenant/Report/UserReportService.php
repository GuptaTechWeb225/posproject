<?php

namespace App\Services\Tenant\Report;

use App\Models\Core\Auth\User;
use App\Services\Core\BaseService;

class UserReportService extends BaseService
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }
}