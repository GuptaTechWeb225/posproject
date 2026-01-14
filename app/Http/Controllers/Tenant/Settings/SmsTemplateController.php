<?php

namespace App\Http\Controllers\Tenant\Settings;


use App\Filters\Tenant\SmsTemplateFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SmsTemplate\SmsTemplateRequest;
use App\Services\Tenant\Setting\SmsTemplateService;
use App\Models\Tenant\SmsTemplate\SmsTemplate;

class SmsTemplateController extends Controller
{

    public function __construct(SmsTemplateService $service, SmsTemplateFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    public function store(SmsTemplateRequest $request)
    {
        if ($request->is_default) {
            $this->service->query()->update([
                'is_default' => false
            ]);
        }
        $this->service->query()->create($request->only(['subject', 'content', 'is_default']));

        return created_responses('sms_template');
    }

    public function show(SmsTemplate $smsTemplate)
    {
        return $smsTemplate;
    }

    public function update(SmsTemplateRequest $smsTemplateRequest, SmsTemplate $smsTemplate)
    {
        if ($smsTemplateRequest->is_default) {
            $this->service->query()->update([
                'is_default' => false
            ]);
        }

        $smsTemplate->update(
            $smsTemplateRequest->only('subject', 'content', 'is_default')
        );

        return updated_responses('sms_template');
    }


    public function destroy(SmsTemplate $smsTemplate)
    {
        $smsTemplate->delete();
        return deleted_responses('sms_template');
    }
}
