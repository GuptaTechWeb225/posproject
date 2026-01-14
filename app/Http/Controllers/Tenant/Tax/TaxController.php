<?php

namespace App\Http\Controllers\Tenant\Tax;

use App\Filters\Tenant\TaxFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Tax\TaxRequest;
use App\Models\Pos\Product\Tax\Tax;
use App\Services\Tenant\Branch\TaxService;
use Illuminate\Support\Facades\DB;

class TaxController extends Controller
{
    public function __construct(TaxService $service, TaxFilter $filter)
    {
        $this->filter = $filter;
        $this->service = $service;
    }

    public function index(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator|array
    {
        $data = $this->service
            ->filters($this->filter)
            ->with('taxTaxes.parentTax')
            ->latest();

        return request()->all ? $data->get() : $data->paginate(request('per_page', 10));
    }

    public function store(TaxRequest $request): array
    {
        DB::transaction(function () use ($request) {

            $tax = $this->service
                ->checkAndResetDefault(new Tax(), $request->is_default)
                ->setAttrs($request->only('type', 'name', 'percentage', 'is_default', 'tax_id'))
                ->storeTax();

            if ($request->type === 'multi_tax') {
                $this->service
                    ->setModel($tax)
                    ->setAttrs($request->only('tax_id'))
                    ->storeTaxTax();
            }

        });

        return created_responses('tax');
    }

    public function show(Tax $tax): object
    {
        return $tax->load('taxTaxes.parentTax');
    }

    public function update(TaxRequest $request, Tax $tax): array
    {
        $this->service
            ->checkAndResetDefault(new Tax(), $request->get('is_default'));

        DB::transaction(function () use ($request, $tax) {
            $this->service
                ->setModel($tax)
                ->setAttrs($request->only('type', 'name', 'percentage', 'is_default', 'tax_id'))
                ->updateTax();

            if ($request->type === 'multi_tax') {
                $this->service
                    ->setModel($tax)
                    ->setAttrs($request->only('tax_id'))
                    ->updateTaxTax();
            }
        });

        return updated_responses('tax', ['tax' => $tax]);
    }

    public function destroy(Tax $tax): array
    {
        $this->service
            ->setModel($tax)
            ->deleteTax();

        return deleted_responses('tax');
    }
}
