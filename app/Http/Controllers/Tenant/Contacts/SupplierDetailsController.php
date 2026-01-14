<?php

namespace App\Http\Controllers\Tenant\Contacts;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Supplier\Supplier;
use App\Services\Core\Auth\UserService;

class SupplierDetailsController extends Controller
{
    public function __construct(UserService $user)
    {
        $this->service = $user;
    }

    public function profilePictureUpload($id)
    {
        $supplier = Supplier::find($id);
        activity()->withoutLogs(fn() => $this->service->storeThumbnail($supplier));
        return updated_responses('profile_picture');
    }

}
