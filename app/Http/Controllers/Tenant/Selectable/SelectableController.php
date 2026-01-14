<?php


namespace App\Http\Controllers\Tenant\Selectable;


use App\Filters\Core\StatusFilter;
use App\Filters\Core\UserFilter;
use App\Filters\Pos\Inventory\BranchOrWarehouseFilter;
use App\Filters\Pos\Inventory\StockFilter;
use App\Filters\Pos\Settings\CountryFilter;
use App\Filters\Tenant\BrandFilter;
use App\Filters\Tenant\CashRegisterFilter;
use App\Filters\Tenant\CategoryFilter;
use App\Filters\Tenant\CustomerFilter;
use App\Filters\Tenant\CustomerGroupFilter;
use App\Filters\Tenant\GroupFilter;
use App\Filters\Tenant\LotFilter;
use App\Filters\Tenant\OrderFilter;
use App\Filters\Tenant\SubCategoryFilter;
use App\Filters\Tenant\SupplierFilter;
use App\Filters\Tenant\UnitFilter;
use App\Filters\Tenant\VariantFilter;
use App\Http\Controllers\Controller;
use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Pos\Inventory\Lot\Lot;
use App\Models\Pos\Inventory\LotVariant\LotVariant;
use App\Models\Pos\Inventory\Stock\Stock;
use App\Models\Pos\Product\Attribute\Attribute;
use App\Models\Pos\Product\Attribute\AttributeVariation;
use App\Models\Pos\Product\Brand\Brand;
use App\Models\Pos\Product\Category\Category;
use App\Models\Pos\Product\Duration\Duration;
use App\Models\Pos\Product\Group\Group;
use App\Models\Pos\Product\Product\Product;
use App\Models\Pos\Product\Product\Variant;
use App\Models\Pos\Product\SubCategory\SubCategory;
use App\Models\Pos\Product\Tax\Tax;
use App\Models\Pos\Product\Unit\Unit;
use App\Models\Pos\Settings\Country;
use App\Models\Tenant\Customer\Customer;
use App\Models\Tenant\Customer\CustomerGroup;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Sales\Cash\CashRegister;
use App\Models\Tenant\Supplier\Supplier;
use App\Models\Tenant\Tag\Tag;
use App\Repositories\Core\Status\StatusRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SelectableController extends Controller
{
    public function selectableBranches(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return BranchOrWarehouse::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new BranchOrWarehouseFilter())
            ->select(['id', 'status_id', 'name', 'type'])
            ->permission()
            ->where([
                'type' => 'branch',
                'status_id' => resolve(StatusRepository::class)->branchorwarehouseActive()
            ])
            ->orderBy('type', 'asc')
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableSuppliers(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return Supplier::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new SupplierFilter())
            ->where('status_id', resolve(StatusRepository::class)->supplierActive())
            ->select(['id', 'name'])
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableReceivedBys(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return User::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->select(['id', 'first_name', 'last_name'])
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableStatuses(Request $request): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return Status::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new StatusFilter())
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableUnits(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return Unit::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new UnitFilter())
            ->select(['id', 'name'])
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableBrands(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return Brand::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new BrandFilter())
            ->select(['id', 'name'])
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableCategories(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return Category::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new CategoryFilter())
            ->where('status_id', resolve(StatusRepository::class)->categoryActive())
            ->select(['id', 'name'])
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableSubCategories($category_id): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return SubCategory::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new SubCategoryFilter())
            ->where('category_id', $category_id)
            ->select(['id', 'name'])
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableGroups(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return Group::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new GroupFilter())
            ->select(['id', 'name'])
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableTags(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return Tag::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->select(['id', 'name', 'color'])
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableTax(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return Tax::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableVariations(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return AttributeVariation::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->select(['id', 'name'])
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableAttributes(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return Attribute::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->select(['id', 'name'])
            ->with('attributeVariations')
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableProducts(): LengthAwarePaginator
    {
        return Product::query()
            ->where('status_id', resolve(StatusRepository::class)->productActive())
            ->select(['id', 'name'])
            ->paginate(request()->per_page ?? 15);
    }

    //list of product's variant. single product also has a variant
    public function selectableVariants(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return Variant::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->where('status_id', resolve(StatusRepository::class)->productActive())
            ->filters(new VariantFilter())
            ->select(['id', 'name'])
            ->orderBy('name', 'asc')
            ->paginate(request()->per_page ?? 15);
    }

    //list of product's variant. single product also has a variant
    //stock adjustment possible only for existing variant in stock
    public function selectableVariantsForStockAdjustment(): LengthAwarePaginator
    {
        $selected = request()->get('selected');
        return Stock::query()
            ->where('branch_or_warehouse_id', request()->branch_or_warehouse_id)
            ->filters(new StockFilter())
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('variant_id', $selected);
                }
            })
            ->select(['id', 'variant_id', 'branch_or_warehouse_id', 'available_qty'])
            ->with('variant:id,name,upc,status_id')
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableLot(): LengthAwarePaginator
    {
        $selected = request()->get('selected');
        return Lot::query()
            ->where('adjusted_with_stock', true)
            ->filters(new LotFilter())
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->select(['id', 'reference_no'])
            ->with([
                'lotVariants:id,lot_id,variant_id',
                'lotVariants.variant:id,name,upc,status_id',
            ])
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableLotVariants(): LengthAwarePaginator
    {
        $selected = request()->get('selected');
        return LotVariant::query()
            ->where('lot_id', \request()->lot_id)
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->select(['id', 'lot_id', 'variant_id'])
            ->with([
                'variant:id,name,upc,status_id',
            ])
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableWarehouses(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return BranchOrWarehouse::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->select(['id', 'name', 'type', 'status_id'])
            ->where([
                'type' => 'warehouse',
                'status_id' => resolve(StatusRepository::class)->branchorwarehouseActive()
            ])
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableDurations(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return Duration::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->select(['id', 'type'])
            ->paginate(request()->per_page ?? 15);
    }

    public function selectablePurchaseStatus()
    {
        $selected = request()->get('selected');

        return Status::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->where([
                'type' => 'purchase_status',
            ])
            ->select('id', 'name')
            ->get();
    }

    public function selectableInternalTransferStatus(): \Illuminate\Database\Eloquent\Collection|array
    {
        $selected = request()->get('selected');

        return Status::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->where([
                'type' => 'internaltransfer',
            ])
            ->select('id', 'name')
            ->get();
    }

    public function selectableBranchesOrWarehouses()
    {
        $selected = request()->get('selected');

        return BranchOrWarehouse::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new BranchOrWarehouseFilter())
            ->select(['id', 'status_id', 'name', 'type'])
            ->permission()
            ->where('status_id', resolve(StatusRepository::class)->branchorwarehouseActive())
            ->orderBy('type', 'asc')
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableManages(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return User::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->select(['id', 'first_name', 'last_name'])
            ->filters(new UserFilter())
            ->where('status_id', resolve(StatusRepository::class)->userActive())
            ->orderBy('first_name', 'asc')
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableUsers(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return User::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->select(['id', 'first_name', 'last_name'])
            ->filters(new UserFilter())
            ->where('status_id', resolve(StatusRepository::class)->userActive())
            ->orderBy('first_name', 'asc')
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableCustomerGroups(): LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return CustomerGroup::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new CustomerGroupFilter())
            ->select(['id', 'name'])
            ->orderBy('name', 'asc')
            ->paginate(request()->per_page ?? 15);
    }


    public function selectableCountries()
    {
        $selected = request()->get('selected');

        return Country::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new CountryFilter())
            ->select('id', 'code', 'name')
            ->get();
    }

    public function selectableCustomers()
    {
        $selected = request()->get('selected');
        $orderBy = request()->get('order_by');

        return Customer::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->when($orderBy, function ($query) use ($orderBy) {
                $query->orderBy('id', $orderBy);
            })
            ->filters(new CustomerFilter())
            ->select('id', 'first_name', 'last_name')
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableCashRegister()
    {
        $selected = request()->get('selected');

        return CashRegister::query()
            ->with([
                'status:id,name,class',
                'createdBy:id,first_name,last_name',
                'openedBy:id,first_name,last_name',
                'closedBy:id,first_name,last_name',
                'branchOrWarehouse:id,name'
            ])
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {
                $builder->where('branch_or_warehouse_id', request('branch_or_warehouse_id'));
            })
            ->filters(new CashRegisterFilter())
            ->select('id', 'branch_or_warehouse_id', 'name', 'opening_time', 'closing_time', 'opening_balance', 'closing_balance', 'created_by', 'status_id','opened_by','closed_by')
            ->paginate(request()->per_page ?? 15);
    }


    public function selectableInvoice()
    {
        return Order::query()
            ->filters(new OrderFilter())
            ->branchOrWarehouse(request()->branch_or_warehouse_id)
            ->select('id', 'invoice_number', 'status_id', 'ordered_at')
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableYear()
    {
        $order = Order::query();
        $data = $order->first();
        if ($data) {
           Carbon::parse($data->ordered_at)->format('Y');
        }
    }

    public function getTax()
    {
        return Tax::query()
            ->select(['id', 'name', 'percentage', 'is_default', 'type'])
            ->get();
    }
}
