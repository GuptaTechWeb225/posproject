<?php

namespace App\Services\Tenant\Report;

use App\Filters\Tenant\OrderFilter;
use App\Models\Tenant\Order\OrderProduct;
use App\Services\Core\BaseService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProfitLossService extends BaseService
{
    public $filter;

    public function __construct(OrderProduct $order, OrderFilter $filter)
    {
        $this->model = $order;
        $this->filter = $filter;
    }

    public function profitLoss()
    {
        return $this->profitLossQuery()
            ->paginate(request('per_page' ?? 10));
    }

    public function profitLossQuery()
    {
        $orders = $this->model->query();

        $searchSelect = request('search_select');

        switch ($searchSelect) {
            case 'month':
                $orders = $this->filterByMonth($orders);
                break;
            case 'year':
                $orders = $this->filterByYear($orders);
                break;
            case 'date':
                $orders = $this->filterByDate($orders);
                break;
            case 'invoice_id':
                $orders = $this->filterByInvoiceId($orders);
                break;
            case 'customer':
                $orders = $this->filterByCustomer($orders);
                break;
            default:
                $orders = $this->filterByDefault($orders);
                break;
        }

        return $orders;
    }

    private function filterByMonth($orders)
    {
        return $orders
            ->when(request()->get('year'), fn(Builder $builder) => $builder->whereYear('ordered_at', request()->get('year')))
                ->select(
                    DB::raw("YEAR(ordered_at) as year"),
                    DB::raw("MONTH(ordered_at) as month"),
                    DB::raw("(DATE_FORMAT(ordered_at, '%M-%Y')) as group_by"),
                    DB::raw("(SUM(avg_purchase_price * quantity)) as total_purchase_amount"),
                    DB::raw("(SUM(sub_total)) as total_sell_amount"),
                    DB::raw("(SUM(sub_total) - SUM(avg_purchase_price * quantity)) as net_profit")
                )
                ->orderBy('year', 'ASC')
                ->orderBy('month', 'ASC')
                ->groupBy(DB::raw("YEAR(ordered_at)"), DB::raw("MONTH(ordered_at)"), DB::raw("DATE_FORMAT(ordered_at, '%M-%Y')"));


    }

    private function filterByYear($orders)
    {
        return $orders->select(
            DB::raw("YEAR(ordered_at) AS group_by"),
            DB::raw("SUM(avg_purchase_price * quantity) as total_purchase_amount"),
            DB::raw("SUM(sub_total) as total_sell_amount"),
            DB::raw("SUM(sub_total) - SUM(avg_purchase_price * quantity) as net_profit")
        )
            ->orderBy('group_by', 'ASC')
            ->whereYear('ordered_at', Carbon::now())
            ->groupBy('group_by');
    }

    private function filterByDate($orders)
    {
        return $orders
            ->leftJoin('orders', 'order_products.order_id', '=', 'orders.id')
            ->leftJoin('return_order_products', 'order_products.order_id', '=', 'return_order_products.order_id')
            ->when(request('date'), function (Builder $builder) {
                $date = json_decode(htmlspecialchars_decode(request('date')), true);
                $builder->whereBetween(DB::raw('DATE(order_products.ordered_at)'), array_values($date));
            })
            ->select(
                'order_products.id as order_product_id',
                DB::raw("(sum(order_products.avg_purchase_price*order_products.quantity)) as total_purchase_amount"),
                DB::raw("(sum(order_products.sub_total)) as total_sell_amount"),
                DB::raw("(sum(order_products.sub_total))-(sum(order_products.avg_purchase_price*order_products.quantity)) as net_profit"),
                DB::raw("DATE_FORMAT(order_products.ordered_at, '%d-%m-%Y') AS group_by"),
                DB::raw("MIN(order_products.ordered_at) as min_ordered_at") // Add the MIN aggregation
            )
            ->orderBy('min_ordered_at', 'ASC') // Use the alias in the ORDER BY clause
            ->groupBy('group_by', 'order_products.id');
    }

    private function filterByInvoiceId($orders)
    {
        return $orders
            ->leftJoin('orders', 'order_products.order_id', '=', 'orders.id')
            ->select(
                'order_products.id as order_products_id',
                'orders.tenant_id as tenant_id',
                'orders.invoice_number as group_by',
                DB::raw("(SUM(order_products.avg_purchase_price*order_products.quantity)) as total_purchase_amount"),
                DB::raw("(SUM(order_products.sub_total)) as total_sell_amount"),
                DB::raw("(SUM(order_products.sub_total)) - (SUM(order_products.avg_purchase_price*order_products.quantity)) as net_profit"),
            )
            ->orderBy('order_products.ordered_at', 'ASC')
            ->groupBy('order_products.id', 'orders.tenant_id', 'orders.invoice_number');
    }

    private function filterByCustomer($orders)
    {
        return $orders
            ->leftJoin('orders', 'order_products.order_id', '=', 'orders.id')
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->select(
                'order_products.id as order_products_id',
                'orders.tenant_id as tenant_id',
                'orders.customer_id',
                DB::raw("(CONCAT(customers.first_name,' ',customers.last_name)) as group_by"),
                DB::raw("(SUM(order_products.avg_purchase_price * order_products.quantity)) as total_purchase_amount"),
                DB::raw("(SUM(order_products.sub_total)) as total_sell_amount"),
                DB::raw("(SUM(order_products.sub_total)) - (SUM(order_products.avg_purchase_price * order_products.quantity)) as net_profit")
            )
            ->orderBy('order_products.ordered_at', 'ASC')
            ->groupBy('order_products.id', 'orders.tenant_id', 'orders.customer_id');
    }

    private function filterByDefault($orders)
    {
        return $orders->select(
            DB::raw("YEAR(ordered_at) AS group_by"),
            DB::raw("SUM(avg_purchase_price * quantity) as total_purchase_amount"),
            DB::raw("SUM(sub_total) as total_sell_amount"),
            DB::raw("SUM(sub_total) - SUM(avg_purchase_price * quantity) as net_profit")
        )
            ->orderBy('group_by', 'ASC')
            ->whereYear('ordered_at', Carbon::now())
            ->groupBy('group_by');
    }


    public function exportProfitLoss()
    {

    }
}