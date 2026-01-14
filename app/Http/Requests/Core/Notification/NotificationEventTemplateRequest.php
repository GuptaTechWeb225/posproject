<?php

namespace App\Http\Requests\Core\Notification;

use App\Http\Requests\BaseRequest;
use App\Models\Core\Setting\NotificationEvent;

class NotificationEventTemplateRequest extends BaseRequest
{

    /**
     * @return array
     * @throws \App\Exceptions\GeneralException
     */
    public function rules()
    {
        return $this->initRules(new NotificationEvent());
    }
}
