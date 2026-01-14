<?php


namespace App\Http\Controllers\Tenant\Branch;


use App\Http\Controllers\Controller;
use App\Models\Tenant\Branch\Branch;
use App\Repositories\Core\Status\StatusRepository;


class BranchStatusController extends Controller
{
    public function update($id)
    {
        $branch = Branch::findOrFail($id);
        $method = $branch->isActive() ? 'branchInactive' : 'branchActive';
        $status_id = resolve(StatusRepository::class)->$method();

        $branch->update([
            'status_id' => $status_id
        ]);

        return updated_responses('branch', ['branch' => $branch]);

    }
}