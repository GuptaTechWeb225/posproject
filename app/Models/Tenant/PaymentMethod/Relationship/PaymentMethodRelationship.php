<?php


namespace App\Models\Tenant\PaymentMethod\Relationship;

use App\Models\Core\Setting\Setting;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Core\Traits\StatusRelationship;
use App\Models\Tenant\Transaction\Transaction;

trait PaymentMethodRelationship
{
    use StatusRelationship, CreatedByRelationship;

    public function settings(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Setting::class, 'settingable');
    }

    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}