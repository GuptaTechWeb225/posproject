<?php


namespace Database\Seeders;


use App\Models\Pos\Inventory\Adjustment\Adjustment;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Pos\Inventory\Lot\Lot;
use App\Models\Pos\Inventory\LotVariant\LotVariant;
use App\Models\Pos\Product\Brand\Brand;
use App\Models\Pos\Product\Category\Category;
use App\Models\Pos\Inventory\AdjustmentVariant;
use App\Models\Pos\Product\Group\Group;
use App\Models\Pos\Product\SubCategory\SubCategory;
use App\Models\Pos\Product\Unit\Unit;
use App\Models\Tenant\Customer\Customer;
use App\Models\Tenant\Customer\CustomerGroup;
use App\Models\Tenant\Expense\Expense;
use App\Models\Tenant\Expense\ExpenseArea;
use App\Models\Tenant\Supplier\Supplier;
use Database\Seeders\Auth\BranchOrWarehouseToUserTableSeeder;
use Database\Seeders\BranchAndWarehouse\BranchAndWarehouseSeeder;
use Database\Seeders\Country\CountrySeeder;
use Database\Seeders\Duration\DurationSeeder;
use Database\Seeders\Payment\PaymentMethodSeeder;
use Database\Seeders\Product\ProductSeeder;
use Database\Seeders\Sales\InvoiceSeeder;
use Database\Seeders\Sales\OrderSeeder;
use Database\Seeders\Settings\EmailSetupSeeder;
use Database\Seeders\Stock\StockSeeder;
use Database\Seeders\Tax\TaxSeeder;
use Database\Seeders\Tenant\AttributeTableSeeder;
use Database\Seeders\Tenant\AttributeVariationTableSeeder;
use Database\Seeders\Tenant\CashRegisterSeeder;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;

    public function run()
    {
        Model::unguard();
        activity()->withoutLogs(function () {
            $this->disableForeignKeys();
            $this->call(EmailSetupSeeder::class);
            $this->call(DatabaseSeeder::class);
            $this->call(TaxSeeder::class);


            //-------------------------------------------
            //-------------------------------------------
            //BranchOrWarehouse::factory(2)->create();
            $this->call(BranchAndWarehouseSeeder::class);
            //-------------------------------------------

            $this->call(PaymentMethodSeeder::class);

            //add branch_or_warehouse_id for user table
            $this->call(BranchOrWarehouseToUserTableSeeder::class);

            CustomerGroup::factory(1)->create();
            Customer::factory(12)->create();
            Supplier::factory(10)->create();
            Category::factory(12)->create();
            Unit::factory(12)->create();
            Group::factory(50)->create();
            Brand::factory(12)->create();
            $this->call(AttributeTableSeeder::class);
            $this->call(AttributeVariationTableSeeder::class);
            SubCategory::factory(12)->create();

            $this->call(ProductSeeder::class);
            Lot::factory(45)->create();
            LotVariant::factory(45)->create();

            //Seed stock and update lot status
            $this->call(StockSeeder::class);
            ExpenseArea::factory(12)->create();
            Expense::factory(12)->create();

            Adjustment::factory(12)->create();
            AdjustmentVariant::factory(12)->create();

            $this->call(DurationSeeder::class);


            $this->call(CountrySeeder::class);
            $this->call(CashRegisterSeeder::class);
            $this->call(OrderSeeder::class);
            $this->call(InvoiceSeeder::class);


            $this->enableForeignKeys();
        });

        Model::reguard();
    }
}