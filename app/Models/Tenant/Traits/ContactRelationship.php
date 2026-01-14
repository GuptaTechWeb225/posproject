<?php

namespace App\Models\Tenant\Traits;

use App\Models\Tenant\Contacts\Contact;
use  Illuminate\Database\Eloquent\Relations\MorphMany;

trait ContactRelationship
{
    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function emails(): MorphMany
    {
        return $this->contacts()
            ->where('name', 'email')
            ->select('name','value','type','contactable_type','contactable_id');
    }
    public function phoneNumbers(): MorphMany
    {
        return $this->contacts()
            ->where('name', 'phone_number')
            ->select('name','value','type','contactable_type','contactable_id');
    }
    
}
