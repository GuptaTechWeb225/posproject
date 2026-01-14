<?php


namespace App\Models\Core\Setting\Traits;

trait NotificationEventRules
{
    public function attachedRules()
    {
        return [
            'templates' => 'required'
        ];
    }
}
