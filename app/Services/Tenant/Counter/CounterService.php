<?php


namespace App\Services\Tenant\Counter;


use App\Models\Tenant\Branch\Counter\Counter;
use App\Models\Tenant\Branch\Counter\CounterSalesPerson;
use App\Models\Tenant\Sales\Cash\CashRegister;
use App\Services\Tenant\TenantService;

class CounterService extends TenantService
{
    public function __construct(CashRegister $counter)
    {
        $this->model = $counter;
    }

    public function update()
    {

        $this->model->fill($this->getAttributes());

        $this->model->save();

        return $this;
    }

    public function store(): CashRegister
    {
        $this->model = $this->save(
            $this->getAttrs('name', 'branch_warehouse_id', 'invoice_template_id', 'created_by', 'tenant_id')
        );

        return $this->model;
    }

    public function storeSalesPerson(): void
    {
        $sales_people = [];

        foreach ($this->getAttr('sales_person') as $sale_person) {
            $sales_people[] = [
                'counter_id' => $this->getModel()->id,
                'sales_person' => $sale_person
            ];
        }

        CounterSalesPerson::query()->insert($sales_people);
    }

    public function updateSalesPerson(): void
    {
        $this->getModel()->salesPeople()->delete();

        foreach ($this->getAttr('sales_person') as $sales_person) {
            CounterSalesPerson::query()->updateOrCreate([
                'counter_id' => $this->getModel()->id,
                'sales_person' => $sales_person
            ]);
        }
    }
}