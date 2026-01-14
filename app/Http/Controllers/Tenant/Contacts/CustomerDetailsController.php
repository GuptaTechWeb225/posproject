<?php

namespace App\Http\Controllers\Tenant\Contacts;

use App\Filters\Tenant\OrderFilter;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Contacts\Contact;
use App\Models\Tenant\Customer\Customer;
use App\Models\Tenant\Order\Order;
use App\Services\Core\Auth\UserService;
use DB;

class CustomerDetailsController extends Controller
{
    public $orderFilter;
    public $order;

    public function __construct(UserService $user, Order $order, OrderFilter $orderFilter)
    {
        $this->service = $user;
        $this->order = $order;
        $this->orderFilter = $orderFilter;
    }

    public function index(Customer $customer)
    {
        $contacts = $customer->contacts->groupBy('name');

        return $customer->setRelation('contacts', $contacts)->load(['customerGroup', 'addresses', 'profilePicture'])
            ->loadSum([
                'orders as total_order' => function ($query) {
                    $query->select(DB::raw('sum(grand_total)'));
                },
                'orders as total_due' => function ($query) {
                    $query->select(DB::raw('sum(due_amount)'));
                }
            ], 'grand_total');
    }

    public function customerInfo($id)
    {
        $customer = Contact::query()->where('contactable_id', $id)->get();
        return $customer;
    }

    public function customerOrders($id)
    {
        return $this->order->query()
            ->filters($this->orderFilter)
            ->with([
                'createdBy:id,first_name,last_name',
                'customer:id,first_name,last_name',
                'branchOrWarehouse:id,name,type',
                'status:id,name,class',
                'cashRegister:id,name'
            ])
            ->where('customer_id', $id)
            ->paginate(
                request('per_page' ?? 10)
            );
    }

    public function profilePictureUpload($id)
    {
        $customer = Customer::find($id);
        activity()->withoutLogs(fn() => $this->service->storeThumbnail($customer));
        return updated_responses('profile_picture');
    }

}
