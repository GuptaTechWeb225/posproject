<?php


namespace App\Services\Tenant\Contact;


use App\Helpers\Core\Traits\Memoization;
use App\Managers\BulkImport\Customer\CustomerImport;
use App\Models\Tenant\Customer\Customer;
use App\Models\Tenant\Customer\CustomerGroup;
use App\Services\Core\BaseService;

class CustomerImportService extends BaseService
{
    use Memoization;

    private CustomerImport $importer;

    public function __construct(Customer $customer)
    {
        $this->model = $customer;
        $this->importer = resolve(CustomerImport::class);
    }

    public function preview(): array
    {
        $file = $this->getAttr('file');
        return $this->importer
            ->setFile($file)
            ->preview();
    }

    public function saveSanitized(): int
    {
        $customers = $this->getAttr('sanitized');

        if (is_array($customers)) {
            $customers = collect($customers);
        }

        $customerGroups = $this->groupFirstOrCrete($customers);

        ['count' => $count, 'customers' => $c] =  $this->buildRows($customers, $customerGroups);

        Customer::query()->insert($c);

        return $count;
    }

    public function store()
    {
        $customers = $this->importer->sanitize(
            $this->getAttr('customers')
        );

        $count = 0;

        if (filled($customers['sanitized'])) {
            $count = $this->setAttr('sanitized', $customers['sanitized'])
                ->saveSanitized();
        }

        unset($customers['sanitized']);

        if (count($customers['filtered'])) {
            $columns = array_keys($customers["filtered"][0]);
            unset($columns[array_search("errorBag", $columns)]);
            return array_merge($customers, ["columns" => $columns]);
        }

        return $count;
    }

    private function customerGroups(): object
    {
        return CustomerGroup::query()
            ->where('tenant_id', tenant()->id)
            ->get();
    }

    private function buildRows($customers, $customerGroups): array
    {
        $builtCustomers = [];
        $count = 0;
        $groups = $customers->groupBy('customer_group');

        foreach ($groups as $k => $group) {
            $builtCustomers = array_map(function ($customer) use ($customerGroups) {
                return [
                    'first_name' => $customer['first_name'],
                    'last_name' => $customer['last_name'],
                    'tin' => $customer['tin'],
                    'customer_group_id' => $customerGroups[
                        ucwords($customer['customer_group'])
                    ],
                    'tenant_id' => tenant()->id,
                    'created_by' => auth()->id()
                ];
            }, $customers->toArray());

            $count += count($builtCustomers);
        }

        return [
            'count' => $count,
            'customers' => $builtCustomers,
        ];
    }

    private function groupFirstOrCrete($customers):object
    {
        $existedGroups = $this->customerGroups();

        return $this->memoize('customer-groups', function () use ($customers, $existedGroups)  {
            return $customers->pluck('customer_group')
                ->map(function ($name) use ($existedGroups) {
                    $existedGroup = $existedGroups
                        ->where('name', ucwords($name))
                        ->first();

                    if ($existedGroup) {
                        return $existedGroup;
                    }

                    return CustomerGroup::query()->create([
                        'name' => ucwords($name),
                        'tenant_id' => tenant()->id
                    ]);

                })->pluck('id', 'name');
        });
    }
}