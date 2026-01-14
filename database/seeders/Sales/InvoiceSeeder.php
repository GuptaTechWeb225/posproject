<?php

namespace Database\Seeders\Sales;

use App\Models\Tenant\InvoiceTemplate\InvoiceTemplate;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TharmalTemplateSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    use TharmalTemplateSeeder;

    public function run()
    {
        InvoiceTemplate::insert($this->tharmalInvoices());
    }

}
