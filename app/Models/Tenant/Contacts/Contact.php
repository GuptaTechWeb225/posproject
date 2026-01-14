<?php

namespace App\Models\Tenant\Contacts;

use App\Models\Tenant\Contacts\Relationship\ContactsRelationship;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends TenantModel
{
    use HasFactory,
        ContactsRelationship;

    protected $fillable = ['name', 'value'];

    protected $hidden = ['contactable_type', 'contactable_id'];
}
