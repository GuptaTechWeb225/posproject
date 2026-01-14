<?php

namespace App\Services\Tenant\Report;

use App\Models\Pos\Product\Product\Product;
use App\Services\Tenant\TenantService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TopSellingProductService extends TenantService
{
    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function topSellingQuery()
    {
        return $this->model->query()
            ->when(request('search'), function (Builder $builder) {
                $search = request('search');
                $builder->where('name', 'LIKE', "%{$search}%");
            })
            ->leftJoin('order_products', 'products.id', '=', 'order_products.order_product_id')
            ->leftJoin('orders', 'order_products.order_id', '=', 'orders.id')
            ->selectRaw('products.id,name, COALESCE(sum(order_products.quantity),0) total_quantity')
            ->selectRaw('COALESCE(sum(order_products.sub_total),0) total_amount')
            ->groupBy('products.id')
            ->when(request('branch_or_warehouse_id') != 'null', function (Builder $builder) {
                $builder->where('orders.branch_or_warehouse_id', request('branch_or_warehouse_id'));
            })
            ->when(request('date'), function (Builder $builder) {
                $date = json_decode(htmlspecialchars_decode(request('date')), true);
                $builder->whereBetween(DB::raw('DATE(orders.ordered_at)'), array_values($date));
            })
            ->when(request('range_filter'), function (Builder $builder) {
                $chargeRange = json_decode(htmlspecialchars_decode(request('range_filter')), true);
                $builder->where('order_products.sub_total', '>=', $chargeRange['min'])
                    ->where('order_products.sub_total', '<=', $chargeRange['max']);
            });
    }

    public function topSellingProducts()
    {
        return $this->topSellingQuery()
            ->orderBy('total_quantity', 'desc')
            ->paginate(request('per_page' ?? 15));
    }


    public function download($batch = 0)
    {
        $export_count = config('excel.exports.chunk_size');

        $skip = ($export_count * $batch) - $export_count;

        $data = $this->mapper();


        $relation = [];

        $orders = getChunk($skip, $export_count, $this->topSellingQuery(), $data, $relation);

        $title = __t('top_selling_report');

        $response =  Excel::download(exportBuilder
        (
            $orders,
            $this->getHeadings(),
            $title
        ), "$title-$batch.xlsx",\Maatwebsite\Excel\Excel::XLSX
        );

        if (ob_get_contents()) ob_end_clean();
        return $response;
    }

    public function getHeadings()
    {
        return [
            __t('name'),
            __t('quantity'),
            __t('total_amount'),
        ];
    }

    public function mapper()
    {
        return fn($model) => [
            'name' => $model->name,
            'total' => $model->total,
            'total_amount' => $model->total_amount,
        ];
    }

}
