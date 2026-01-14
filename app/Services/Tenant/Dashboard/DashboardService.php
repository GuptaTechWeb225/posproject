<?php

namespace App\Services\Tenant\Dashboard;

use App\Filters\Pos\Inventory\StockFilter;
use App\Filters\Tenant\CustomerFilter;
use App\Filters\Tenant\VariantFilter;
use App\Models\Pos\Inventory\Lot\Lot;
use App\Models\Pos\Inventory\Stock\Stock;
use App\Models\Pos\Product\Product\Product;
use App\Models\Pos\Product\Product\Variant;
use App\Models\Tenant\Expense\Expense;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Order\OrderProduct;
use App\Models\Tenant\Order\ReturnOrderProduct;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\Stock\StockService;
use App\Services\Tenant\TenantService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DashboardService extends TenantService
{
    protected Order $order;
    protected Expense $expense;
    protected Product $product;
    protected Variant $variant;
    protected VariantFilter $variantFilter;
    protected StockService $stockService;
    protected StockFilter $stockFilter;
    protected CustomerFilter $customerFilter;
    protected Lot $lot;
    protected Stock $stock;
    protected OrderProduct $orderProduct;
    protected ReturnOrderProduct $returnOrderProduct;

    public function __construct(Order $order, Expense $expense, Product $product, StockService $stockService, VariantFilter $variantFilter, CustomerFilter $customerFilter, Variant $variant, Lot $lot, Stock $stock, OrderProduct $orderProduct, ReturnOrderProduct $returnOrderProduct)
    {
        $this->order = $order;
        $this->expense = $expense;
        $this->product = $product;
        $this->variant = $variant;
        $this->stockService = $stockService;
        $this->variantFilter = $variantFilter;
        $this->customerFilter = $customerFilter;
        $this->lot = $lot;
        $this->stock = $stock;
        $this->returnOrderProduct = $returnOrderProduct;
        $this->orderProduct = $orderProduct;
    }

    public function highlightsInfo(): array
    {
        $totalExpense = $this->expense
            ->query()
            ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {
                $builder->branchOrWarehouse(request('branch_or_warehouse_id'));
            })
            ->when(request()->get('date'), function (Builder $builder) {
                $type = request()->get('date');
                $this->dateQueryWithType($builder, 'expense_date', $type);
            })
            ->sum('amount');

        $order = $this->order
            ->query()
            ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {

                $role = auth()->user()->roles[0]; // for single role
                if ($role->is_admin == true || $role->is_manager == true || $role->is_warehouse_manager == true) {
                    $builder->where('branch_or_warehouse_id', request('branch_or_warehouse_id'));

                    //cashier
                } elseif($role->id === 5) {
                    $builder->where('created_by', auth()->id());

                }else{
                    $builder->where('branch_or_warehouse_id', auth()->user()->branch_or_warehouse_id); //
                }
            })
            ->when(request()->get('date'), function (Builder $builder) {
                $date = request()->get('date');
                $this->dateQueryWithType($builder, 'ordered_at', $date);
            });

        $profitQuery = $this->orderProduct
            ->query()
            ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {
                $builder->where('branch_or_warehouse_id', request('branch_or_warehouse_id'));
            })
            ->when(request()->get('date'), function (Builder $builder) {
                $date = request()->get('date');
                $this->getNetProfitLoss($builder, 'ordered_at', $date);
            })->selectRaw('SUM(sub_total-avg_purchase_price) as profit_loss')->first();

        $totalSales = $order->sum('grand_total');

        $totalDue = $order->where('status_id', resolve(StatusRepository::class)->orderDue())->sum('due_amount');

        $netProfit = $profitQuery->profit_loss;

        return [
            'totalSales' => $totalSales,
            'netProfit' => $netProfit,
            'totalExpense' => $totalExpense,
            'totalDue' => $totalDue
        ];
    }

    public function getNetProfitLoss($query, string $column, string $type)
    {
        if ($type == 'this_month') {
            $year = Carbon::now()->format('Y');
            $month = Carbon::now()->format('m');

            $query->whereYear($column, $year)->whereMonth($column, $month)->select(
                "id",
                DB::raw("(sum(avg_purchase_price*quantity)) as total_purchase_amount"),
                DB::raw("(sum(sub_total)) as total_sell_amount"),
                DB::raw("(sum(sub_total))-(sum(avg_purchase_price*quantity)) as net_profit"),
            );

        } elseif ($type == 'last_month') {
            $month = Carbon::now()->subMonth()->format('m');
            if ($month == 12) {
                $year = Carbon::now()->subYear()->format('Y');
                $query->whereYear($column, $year)->whereMonth($column, $month)->select(
                    "id",
                    DB::raw("(sum(avg_purchase_price*quantity)) as total_purchase_amount"),
                    DB::raw("(sum(sub_total)) as total_sell_amount"),
                    DB::raw("(sum(sub_total))-(sum(avg_purchase_price*quantity)) as net_profit"),
                );
            } else {
                $year = Carbon::now()->format('Y');
                $month = Carbon::now()->subMonth()->format('m');
                $query->whereYear($column, $year)->whereMonth($column, $month)->select(
                    "id",
                    DB::raw("(sum(avg_purchase_price*quantity)) as total_purchase_amount"),
                    DB::raw("(sum(sub_total)) as total_sell_amount"),
                    DB::raw("(sum(sub_total))-(sum(avg_purchase_price*quantity)) as net_profit"),
                );
            }
        } elseif ($type == 'last_week') {
            $lastWeekStartDay = Carbon::now()->subDays(7)->format('Y-m-d');
            $lastWeekEndDay = Carbon::now()->subDays(1)->format('Y-m-d');
            $query->whereBetween($column, [$lastWeekStartDay, $lastWeekEndDay]);
        } elseif ($type == 'this_year') {
            $year = Carbon::now()->format('Y');
            $query->whereYear($column, $year)->select(
                "id",
                DB::raw("(sum(avg_purchase_price*quantity)) as total_purchase_amount"),
                DB::raw("(sum(sub_total)) as total_sell_amount"),
                DB::raw("(sum(sub_total))-(sum(avg_purchase_price*quantity)) as net_profit"),
            );
        } elseif ($type == 'last_year') {
            $year = Carbon::now()->subYear()->format('Y');
            $query->whereYear($column, $year)->select(
                "id",
                DB::raw("(sum(avg_purchase_price*quantity)) as total_purchase_amount"),
                DB::raw("(sum(sub_total)) as total_sell_amount"),
                DB::raw("(sum(sub_total))-(sum(avg_purchase_price*quantity)) as net_profit"),
            );
        }
    }

    public function dateQueryWithType($query, string $column, string $type)
    {

        if ($type == 'this_month') {
            $year = Carbon::now()->format('Y');
            $month = Carbon::now()->format('m');
            $query->whereYear($column, $year)->whereMonth($column, $month);

        } elseif ($type == 'last_month') {
            $month = Carbon::now()->subMonth()->format('m');
            if ($month == 12) {
                $year = Carbon::now()->subYear()->format('Y');
                $query->whereYear($column, $year)->whereMonth($column, $month);
            } else {
                $year = Carbon::now()->format('Y');
                $month = Carbon::now()->subMonth()->format('m');
                $query->whereYear($column, $year)->whereMonth($column, $month);
            }
        } elseif ($type == 'last_week') {
            $lastWeekStartDay = Carbon::now()->subDays(7)->format('Y-m-d');
            $lastWeekEndDay = Carbon::now()->subDays(1)->format('Y-m-d');
            $query->whereBetween($column, [$lastWeekStartDay, $lastWeekEndDay]);
        } elseif ($type == 'this_year') {
            $year = Carbon::now()->format('Y');
            $query->whereYear($column, $year);
        } elseif ($type == 'last_year') {
            $year = Carbon::now()->subYear()->format('Y');
            $query->whereYear($column, $year);
        }
    }

    public function recentSales()
    {
        return $this->order->query()
            ->branchOrWarehouse(request()->branch_or_warehouse_id)
            ->orderByDesc('id')
            ->with(['status:id,name,class', 'customer:id,first_name,last_name'])
            ->take(7)
            ->get();
    }

    public function topSellingProducts()
    {
        $products = Product::query()
            ->leftJoin('order_products', 'products.id', '=', 'order_products.order_product_id')
            ->leftJoin('orders', 'order_products.order_id', '=', 'orders.id')
            ->selectRaw('products.id,name, COALESCE(sum(order_products.quantity),0) total')
            ->groupBy('products.id','products.name')
            ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {
                $builder->where('orders.branch_or_warehouse_id', $this->getAttribute('branch_or_warehouse_id'));
            })
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
        $label = [];
        $series = [];
        foreach ($products as $key => $product) {
            $label[] = $product->name;
            $series[] = $product->total;
        }
        return [
            'label' => $label,
            'series' => $series
        ];
    }

    public function topCustomers()
    {
        return $this->order->query()
            ->with('customer:id,first_name,last_name,email')
            ->whereHas('customer')
            ->when(request('search'), function (Builder $builder) {
                $builder->whereHas('customer', function (Builder $builder) {
                    $search = request('search');
                    $builder->where('first_name', 'LIKE', "%$search%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->orWhereRaw(DB::raw('CONCAT(`first_name`, " ", `last_name`) LIKE ?'), ["%$search%"]);
                });
            })
            ->addSelect(DB::raw('SUM(grand_total) as purchase_total, customer_id'))
            ->groupBy('customer_id')
            ->orderBy('purchase_total', 'DESC')
            ->branchOrWarehouse(request()->branch_or_warehouse_id)
            ->take(10)
            ->get();
    }

    public function stockSummary()
    {
        return $this->variant->query()
            ->filters($this->variantFilter)
            ->leftJoin('stocks', 'variants.id', '=', 'stocks.variant_id')
            ->select('stocks.id as stock_id', 'stocks.variant_id', 'stocks.branch_or_warehouse_id', 'stocks.available_qty',
                'variants.id as variant_id', 'variants.name', 'variants.upc', 'variants.stock_reminder_quantity')
            ->where('stocks.id', '!=', 'null')
            ->orderBy('stocks.available_qty', 'ASC')
            ->take(10)
            ->get();
    }

    public function purchaseSaleStatistics(): array
    {
        if (request('date') === 'last_week') {
            return $this->weeklyStatistics('monday');
        } elseif (request('date') === 'this_week') {
            return $this->weeklyStatistics('sunday');
        } elseif (request('date') === 'this_month') {
            return $this->monthlyStatistics(date('Y-m-d'));
        } elseif (request('date') === 'last_month') {
            $lastMonth = new Carbon('last month');
            return $this->monthlyStatistics($lastMonth);
        } elseif (request('date') === 'this_year') {
            $year = Carbon::now()->format('Y');
            return $this->yearlyStatistics($year);
        } elseif (request('date') === 'last_year') {
            $year = Carbon::now()->subYear()->format('Y');
            return $this->yearlyStatistics($year);
        } else {
            return [
                'label' => [],
                'sales' => [],
                'purchase' => [],
            ];
        }
    }

    public function weeklyStatistics($date): array
    {
        $firstDayOfLastWeek = strtotime("last {$date}", strtotime('tomorrow'));
        $lastDayOfLastWeek = strtotime('+6 days', $firstDayOfLastWeek);
        $lastWeeksDate = $this->getBetweenDates($firstDayOfLastWeek, $lastDayOfLastWeek);
        $lasWeekDateStringArray = [];
        $lastWeekPurchaseArray = [];
        $lasWeekOrderArray = [];

        foreach ($lastWeeksDate as $key => $item) {
            $lasWeekDateStringArray[] = Carbon::parse($item)->format('D');
            $lastWeekPurchaseArray[] = $this->lot->query()
                ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {
                    $builder->branchOrWarehouse(request('branch_or_warehouse_id'));
                })
                ->where('status_id', resolve(StatusRepository::class)->purchaseDelivered())
                ->whereDate('updated_at', $item)
                ->sum('total_amount');
            $lasWeekOrderArray[] = $this->order->query()
                ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {
                    $builder->branchOrWarehouse(request('branch_or_warehouse_id'));
                })
                ->whereDate('ordered_at', $item)
                ->sum('grand_total');

        }

        return [
            'label' => $lasWeekDateStringArray,
            'sales' => $lasWeekOrderArray,
            'purchase' => $lastWeekPurchaseArray,
        ];
    }

    public function monthlyStatistics($date): array
    {
        $date = Carbon::parse($date);
        $startOfMonth = $date->copy()->startOfMonth()->subDay();
        $endOfMonth = $date->copy()->endOfMonth()->format('d');
        $monthDays = [];
        $purchaseArray = [];
        $saleArray = [];

        for ($i = 0; $i < $endOfMonth; $i++) {
            $dateObj = $startOfMonth->addDay()->startOfDay()->copy();
            $date = Carbon::parse($dateObj)->format('Y-m-d');
            $monthDays[] = Carbon::parse($dateObj)->format('d');
            $purchaseArray[] = $this->lot->query()
                ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {
                    $builder->branchOrWarehouse(request('branch_or_warehouse_id'));
                })
                ->where('status_id', resolve(StatusRepository::class)->purchaseDelivered())
                ->whereDate('updated_at', $date)
                ->sum('total_amount');
            $saleArray[] = $this->order->query()
                ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {
                    $builder->branchOrWarehouse(request('branch_or_warehouse_id'));
                })
                ->whereDate('ordered_at', $date)
                ->sum('grand_total');
        }

        return [
            'label' => $monthDays,
            'sales' => $saleArray,
            'purchase' => $purchaseArray,
        ];
    }

    public function yearlyStatistics($year): array
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $translatedMonths = [__t('jan'), __t('feb'), __t('mar'), __t('apr'), __t('may'), __t('jun'), __t('jul'), __t('aug'), __t('sep'), __t('oct'), __t('nov'), __t('dec')];

        $purchaseArray = [];
        $saleArray = [];

        foreach ($months as $key => $month) {
            $monthString = $year . '-' . $key + 1;
            $getMonth = Carbon::parse($monthString)->format('m');

            $purchaseArray[] = $this->lot->query()
                ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {
                    $builder->branchOrWarehouse(request('branch_or_warehouse_id'));
                })
                ->where('status_id', resolve(StatusRepository::class)->purchaseDelivered())
                ->whereYear('updated_at', $year)
                ->whereMonth('updated_at', $getMonth)
                ->sum('total_amount');
            $saleArray[] = $this->order->query()
                ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {
                    $builder->branchOrWarehouse(request('branch_or_warehouse_id'));
                })
                ->whereYear('ordered_at', $year)
                ->whereMonth('ordered_at', $getMonth)
                ->sum('grand_total');
        }

        return [
            'label' => $translatedMonths,
            'sales' => $saleArray,
            'purchase' => $purchaseArray,
        ];
    }

    function getBetweenDates($startDate, $endDate): array
    {
        $rangArray = [];
        for ($currentDate = $startDate; $currentDate <= $endDate;
             $currentDate += (86400)) {

            $date = date('Y-m-d', $currentDate);
            $rangArray[] = $date;
        }
        return $rangArray;
    }


}