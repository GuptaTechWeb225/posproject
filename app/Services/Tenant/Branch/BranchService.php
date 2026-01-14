<?php

namespace App\Services\Tenant\Branch;

use App\Models\Core\Status;
use App\Models\Tenant\Branch\Branch;
use App\Services\Tenant\TenantService;

class BranchService extends TenantService
{
    public function __construct(Branch $branch)
    {
        $this->model = $branch;
    }

    public function getBranchStatus()
    {
        return Status::where('type', 'branch')->get();
    }

    public function saveCashRegister($branch)
    {

        $branch->counters()
            ->create([
                'name' => 'Main Cash Register',
                'tenant_id' => 1,
                'status_id' => 6,
                'invoice_template_id' => 1
            ]);
    }
}
