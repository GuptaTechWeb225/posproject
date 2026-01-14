<?php

namespace App\Http\Controllers\Tenant\InvoiceTemplate;

use App\Filters\Tenant\InvoiceTemplateFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\InvoiceTemplate\InvoiceTemplateRequest;
use App\Models\Tenant\InvoiceTemplate\InvoiceTemplate;
use App\Services\Tenant\InvoiceTemplate\InvoiceTemplateService;

class InvoiceTemplateController extends Controller
{

    public function __construct(InvoiceTemplateService $service, InvoiceTemplateFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    //will return only sales invoice
    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->orderByDesc('id')
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    public function store(InvoiceTemplateRequest $request)
    {
        $this->service
            ->checkAndResetDefault(new InvoiceTemplate(), $request->is_default)
            ->save(
                $request->only('name', 'default_content', 'custom_content', 'type', 'is_default', 'created_by')
            );

        return created_responses('invoice_template');
    }

    public function show(InvoiceTemplate $invoiceTemplate)
    {
        return $invoiceTemplate;
    }

    public function update(InvoiceTemplateRequest $request, InvoiceTemplate $invoiceTemplate)
    {
        if ($request->is_default == 1 && $invoiceTemplate->is_default == 0){
            $this->service
                ->checkAndResetDefault(new InvoiceTemplate(), $request->is_default);
        }

        $invoiceTemplate->update(
            $request->only('name', 'default_content', 'custom_content', 'type', 'is_default', 'created_by')
        );

        return updated_responses('invoice_template');
    }

    public function destroy(InvoiceTemplate $invoiceTemplate)
    {
        if ($invoiceTemplate->is_default) {
            throw new GeneralException(__t('action_not_allowed'));
        }
        $invoiceTemplate->delete();

        return deleted_responses('invoice_template');
    }
}
