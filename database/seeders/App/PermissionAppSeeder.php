<?php

namespace Database\Seeders\App;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Type;
use Database\Seeders\App\Traits\AddressPermissionTrait;
use Database\Seeders\App\Traits\AttributePermissionTrait;
use Database\Seeders\App\Traits\BranchWarehousePermissionTrait;
use Database\Seeders\App\Traits\BrandPermissionTrait;
use Database\Seeders\App\Traits\CategoryPermissionTrait;
use Database\Seeders\App\Traits\CustomerGroupPermissionTrait;
use Database\Seeders\App\Traits\CustomersPermissionTrait;
use Database\Seeders\App\Traits\ExpenseAreasPermissionTrait;
use Database\Seeders\App\Traits\ExpensesPermissionTrait;
use Database\Seeders\App\Traits\GenerateAndPrintBarOrQrCodePermissionTrait;
use Database\Seeders\App\Traits\GroupPermissionTrait;
use Database\Seeders\App\Traits\InternalTransferPermissionTrait;
use Database\Seeders\App\Traits\InvoiceDuePermissionTrait;
use Database\Seeders\App\Traits\LotPermissionTrait;
use Database\Seeders\App\Traits\ProductPermissionTrait;
use Database\Seeders\App\Traits\SalesInvoicePermissionTrait;
use Database\Seeders\App\Traits\ReportsPermissionTrait;
use Database\Seeders\App\Traits\SalesReturnPermissionTrait;
use Database\Seeders\App\Traits\SalesViewPermissionTrait;
use Database\Seeders\App\Traits\StockAdjustmentPermission;
use Database\Seeders\App\Traits\StockPermissionTrait;
use Database\Seeders\App\Traits\SubCategoryPermissionTrait;
use Database\Seeders\App\Traits\SuppliersPermissionTrait;
use Database\Seeders\App\Traits\UnitPermissionTrait;
use Database\Seeders\Auth\Traits\DashboardPermissionTrait;
use Database\Seeders\Auth\Traits\RolePermissionTrait;
use Database\Seeders\Auth\Traits\SettingPermissionTrait;
use Database\Seeders\Auth\Traits\TemplatePermissionTrait;
use Database\Seeders\Auth\Traits\UserPermissionTrait;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class PermissionAppSeeder extends Seeder
{
    use DisableForeignKeys, RolePermissionTrait, SettingPermissionTrait, TemplatePermissionTrait, UserPermissionTrait,
        LotPermissionTrait, BranchWarehousePermissionTrait, StockPermissionTrait, StockAdjustmentPermission,
        GenerateAndPrintBarOrQrCodePermissionTrait, InternalTransferPermissionTrait, ProductPermissionTrait,
        GroupPermissionTrait, CategoryPermissionTrait, SubCategoryPermissionTrait, BrandPermissionTrait, UnitPermissionTrait,
        AttributePermissionTrait, DashboardPermissionTrait, ExpensesPermissionTrait, ExpenseAreasPermissionTrait,
        CustomersPermissionTrait, SuppliersPermissionTrait, CustomerGroupPermissionTrait, ReportsPermissionTrait,
        CustomersPermissionTrait, SuppliersPermissionTrait, CustomerGroupPermissionTrait, SalesViewPermissionTrait,
        SalesReturnPermissionTrait, SalesInvoicePermissionTrait, AddressPermissionTrait ,InvoiceDuePermissionTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        Permission::query()->truncate();
        $appId = Type::findByAlias('app')->id;

        $core_permissions = array_merge(
            $this->dashboard($appId),
            $this->setting($appId),
            $this->user($appId),
            $this->role($appId),
            $this->template($appId)
        );

        $permission = array_merge(
            $core_permissions,
            $this->lot($appId),
            $this->branchWarehouse($appId),
            $this->stock($appId),
            $this->stockAdjustment($appId),
            $this->barcode_qrcode($appId),
            $this->internalTransfer($appId),
            $this->product($appId),
            $this->group($appId),
            $this->category($appId),
            $this->subcategory($appId),
            $this->brand($appId),
            $this->unit($appId),
            $this->attribute($appId),
            $this->expense($appId),
            $this->expense_areas($appId),
            $this->customers($appId),
            $this->suppliers($appId),
            $this->addresses($appId),
            $this->customer_group($appId),
            $this->sales_view($appId),
            $this->sales_return($appId),
            $this->sales_invoice($appId),
            $this->reports($appId),
            $this->invoice_due($appId)
        );

        $this->enableForeignKeys();
        Permission::query()->insert($permission);
    }
}
