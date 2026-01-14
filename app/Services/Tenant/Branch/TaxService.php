<?php

namespace App\Services\Tenant\Branch;

use App\Exceptions\GeneralException;
use App\Models\Pos\Product\Tax\Tax;
use App\Models\Pos\Product\Tax\TaxTax;
use App\Services\Tenant\TenantService;

class TaxService extends TenantService
{
    public function __construct(Tax $tax)
    {
        $this->model = $tax;
    }

    public function storeTax(): Tax
    {
        $attrs = $this->getAttrs('name', 'type', 'is_default', 'tax_id');
        $attrs['percentage'] = $this->getAttr('type') === 'multi_tax' ? $this->getMultiTaxPercentage() : $this->getAttr('percentage');

        return $this->model->create($attrs);
    }

    private function getMultiTaxPercentage(): float
    {
        $taxIds = $this->getAttr('tax_id');
        return Tax::query()->whereIn('id', $taxIds)->sum('percentage');
    }

    public function storeTaxTax(): void
    {
        $taxes = [];

        foreach ($this->getAttr('tax_id') as $tax_id) {
            $taxes[] = [
                'parent_id' => $tax_id,
                'tax_id' => $this->getModel()->id
            ];
        }

        $this->model
            ->taxTaxes()
            ->insert($taxes);
    }

    public function updateTax()
    {
        $this->getModel()
            ->update([
                'name' => $this->getAttr('name'),
                'type' => $this->getAttr('type'),
                'is_default' => $this->getAttr('is_default'),
                'percentage' => $this->getAttr('type') === 'multi_tax' ? $this->getMultiTaxPercentage() : $this->getAttr('percentage'),
            ]);

        return $this;
    }

    public function updateTaxTax(): void
    {
        $this->getModel()
            ->taxTaxes()
            ->delete();

        $this->storeTaxTax();
    }

    private function groupTax()
    {
        $count_tax_tax = TaxTax::query()
            ->where('parent_id', $this->getModel()->id)
            ->count();

        throw_if($count_tax_tax, new GeneralException(__t('the_tax_is_in_use')));
        throw_if($this->getModel()->is_default, new GeneralException(__t('the_tax_is_in_use')));
    }

    public function deleteTax()
    {
        $this->groupTax();
        $this->getModel()->taxTaxes()->delete();
        $this->getModel()->delete();
    }

    public function deleteGroupTax()
    {
        $this->groupTax();
        $this->getModel()->taxTaxes()->delete();
    }
}
