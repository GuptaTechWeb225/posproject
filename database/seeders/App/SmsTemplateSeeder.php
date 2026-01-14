<?php

namespace Database\Seeders\App;

use App\Models\Tenant\SmsTemplate\SmsTemplate;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class SmsTemplateSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {

        SmsTemplate::query()->create(
            [
                'is_default' => 1,
                'subject' => "Sales sms",
                'content' => "Hi, {first_name} Thanks for shopping with {company_name}. Your invoice is {invoice_id}, Total amount: {total}"
            ]);

    }
}
