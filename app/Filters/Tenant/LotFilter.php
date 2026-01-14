<?php


namespace App\Filters\Tenant;


use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class LotFilter extends FilterBuilder
{
    public function searchByLotReferenceNo($value = null)
    {
        $this->builder->where('reference_no', 'LIKE', "%{$value}%");
    }

    public function search($search = null)
    {
        $this->builder->when($search, function (Builder $builder) use ($search) {
            $builder->where('name', 'LIKE', "%{$search}%");
        });
    }

    public function arrivalDate($date = null)
    {
        $date = json_decode(htmlspecialchars_decode($date), true);

        $this->builder->when($date, function (Builder $builder) use ($date) {
            $builder->whereBetween(\DB::raw('DATE(arrival_date)'), array_values($date));
        });
    }

    public function receivedAt($date = null)
    {
        $date = json_decode(htmlspecialchars_decode($date), true);

        $this->builder->when($date, function (Builder $builder) use ($date) {
            $builder->whereBetween(\DB::raw('DATE(received_at)'), array_values($date));
        });
    }

    public function branches($branch = null)
    {
        $this->builder->when($branch, function (Builder $builder) use ($branch) {
            $builder->where('branch_id', $branch);
        });
    }

    public function receivedBy($receivedBy = null)
    {
        $this->builder->when($receivedBy, function (Builder $builder) use ($receivedBy) {
            $builder->where('received_by', $receivedBy);
        });
    }
}