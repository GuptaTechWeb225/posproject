<?php

namespace App\Services\Tenant\SalesReturn;

use App\Models\Core\Setting\Setting;
use App\Models\Pos\Inventory\Stock\Stock;
use App\Models\Tenant\InvoiceTemplate\InvoiceTemplate;
use App\Models\Tenant\Order\OrderProduct;
use App\Models\Tenant\Order\ReturnOrder;
use App\Services\Core\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SalesReturnService extends BaseService
{
    public $lastInvoiceNumber;
    public $invoiceNumber;
    protected $setting;

    public function __construct(ReturnOrder $returnOrder, Setting $setting)
    {
        $this->model = $returnOrder;
        $this->setting = $setting;
    }


    public function maxMinPriceAmount(): array
    {
        $maxCharge = $this->model->query()->max('sub_total');
        $minCharge = $this->model->query()->min('sub_total');

        return [
            'maxRange' => $maxCharge,
            'minRange' => $minCharge
        ];
    }

    public function store()
    {
        $returnOrderInfo = array_merge($this->getAttributes(
            'branch_or_warehouse_id',
            'customer_id',
            'order_invoice_number',
            'order_id',
            'sub_total',
            'order_invoice_number',
            'reference_number',
            'note',
            'created_by',
            'tenant_id'),
            [
                'returned_at' => date('Y-m-d'),
                'type' => $this->checkReturnType(),
                'invoice_number' => $this->invoiceNumber,
                'last_invoice_number' => $this->lastInvoiceNumber
            ]);
        $this->model->fill($returnOrderInfo)->save();

        return $this;
    }

    public function checkReturnType()
    {
        $orderedQuantity = OrderProduct::where('order_id', $this->getAttribute('order_id'))->sum('quantity');
        if ($orderedQuantity > $this->getAttribute('total_returned_quantity')) {
            return 'partial';
        } elseif ($orderedQuantity == $this->getAttribute('total_returned_quantity')) {
            return 'all';
        } else {
            return 'all';
        }
    }

    public function storeReturnOrderProduct()
    {
        $orderProducts = [];

        if (request()->return_order_products) {
            foreach (request()->return_order_products as $orderProduct) {
                $orderProducts[] = $this->orderProductRequest($orderProduct);
            }
        }

        $this->model
            ->returnOrderProducts()
            ->insert($orderProducts);

        return $this;
    }

    public function makeTransactions()
    {
        $transactions = [
            'created_by' => $this->getAttribute('created_by'),
            'transaction_at' => Carbon::now(),
            'transactionable_type' => get_class($this->model),
            'transactionable_id' => $this->model->id,
            'amount' => -$this->getAttribute('sub_total'),
            'tenant_id' => $this->getAttribute('tenant_id'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $this->model
            ->transactions()
            ->insert($transactions);

        return $this;
    }

    private function orderProductRequest($orderProduct): array
    {
        $this->updateStockQuantity(
            $this->getAttribute('branch_or_warehouse_id'),
            $orderProduct['stock_id'],
            $orderProduct['variant_id'],
            $orderProduct['return_quantity']
        );

        return [
            'return_order_id' => $this->model->id,
            'order_id' => $this->getAttribute('order_id'),
            'stock_id' => $orderProduct['stock_id'],
            'order_product_id' => $orderProduct['order_product_id'],
            'variant_id' => $orderProduct['variant_id'],
            'price' => $orderProduct['price'],
            'quantity' => $orderProduct['return_quantity'],
            'tax_amount' => $orderProduct['tax_amount'] ?? 0,
            'discount_type' => $orderProduct['discount_type'],
            'discount_value' => $orderProduct['discount_value'],
            'discount_amount' => $orderProduct['discount_amount'],
            'sub_total' => $orderProduct['sub_total'],
            'tenant_id' => $this->getAttribute('tenant_id')
        ];
    }

    public function updateStockQuantity($branchId, $stockId, $variantId, $quantity)
    {
        $stock = Stock::query()
            ->where([
                'branch_or_warehouse_id' => $branchId,
                'id' => $stockId,
                'variant_id' => $variantId
            ])->first();

        if($stock){
            $stock->increment('available_qty', $quantity);
            $stock->decrement('total_sales_qty', $quantity);
            $stock->save();
        }

        return $this;
    }

    public function generateInvoiceId()
    {

        $lastReturnOrder = ReturnOrder::query()
            ->select('last_invoice_number')
            ->where('branch_or_warehouse_id', request()->branch_or_warehouse_id)
            ->orderBy('id', 'desc')
            ->first();

        $prefix = $this->setting->query()
            ->where('name', 'return_invoice_prefix')
            ->first();
        $prefix = $prefix ? $prefix->value . '-' : '';

        $suffix = $this->setting->query()
            ->where('name', 'return_invoice_suffix')
            ->first();
        $suffix = $suffix ? '-' . $suffix->value : '';

        if ($lastReturnOrder) {
            $this->invoiceNumber = $prefix . ++$lastReturnOrder->last_invoice_number . $suffix;
            $this->lastInvoiceNumber = $lastReturnOrder->last_invoice_number;
        } else {
            $invoiceStartFrom = $this->setting->query()
                ->where('name', 'sales_invoice_starts_from')
                ->first();

            $this->invoiceNumber = $prefix . $invoiceStartFrom->value . $suffix;

            $this->lastInvoiceNumber = $invoiceStartFrom->value;
        }
        return $this;
    }

    public function generateReturnInvoiceTemplate(): array
    {
        $template = InvoiceTemplate::where([
            'is_default' => 1,
            'type' => 'return_invoice'
        ])->first();

        $invoiceTemplate = $template->custom_content ? $template->custom_content : $template->default_content;

        if (strpos($invoiceTemplate, '{logo_source}')) {
            $invoiceLogo = Setting::query()->where('name', 'return_invoice_logo')->first();
            $logo = $invoiceLogo->value ? asset($invoiceLogo->value) : \App\Http\Composer\Helper\LogoIcon::new(true)->logoIcon()['logo'];
            $invoiceTemplate = Str::replace("{logo_source}", $logo, $invoiceTemplate);
        }


        if (strpos($invoiceTemplate, '{company_phone}')) {
            $invoiceTemplate = Str::replace("{company_phone}", $this->model->branchOrWarehouse->phone, $invoiceTemplate);
        }
        if (strpos($invoiceTemplate, '{company_email}')) {
            $invoiceTemplate = Str::replace("{company_email}", $this->model->branchOrWarehouse->email, $invoiceTemplate);
        }
        if (strpos($invoiceTemplate, '{company_address}')) {
            $invoiceTemplate = Str::replace("{company_address}", $this->model->branchOrWarehouse->name, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{company_name}')) {
            $invoiceTemplate = Str::replace("{company_name}", settings('company_name'), $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{date}')) {
            $date = Carbon::parse($this->model->returned_at)->format('h:i A');
            $invoiceTemplate = Str::replace("{date}", $date, $invoiceTemplate);
        }


        if (strpos($invoiceTemplate, '{invoice_number}')) {
            $invoiceTemplate = Str::replace("{invoice_number}", $this->model->invoice_number, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{cash_counter}')) {
            $invoiceTemplate = Str::replace("{cash_counter}", $this->model->order->cashRegister->name, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{customer_name}')) {
            $invoiceTemplate = Str::replace("{customer_name}", $this->model->customer->full_name, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{employee_name}')) {
            $invoiceTemplate = Str::replace("{employee_name}", $this->model->createdBy->full_name, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{phone_number}')) {
            if ($this->model->customer->phoneNumbers->count() > 0) {
                $invoiceTemplate = Str::replace("{phone_number}", $this->model->customer->phoneNumbers[0]->value, $invoiceTemplate);
            } else {
                $invoiceTemplate = Str::replace("{phone_number}", "", $invoiceTemplate);
            }
        }
        if (strpos($invoiceTemplate, '{address}')) {
            if ($this->model->customer->addresses->count() > 0) {
                $invoiceTemplate = Str::replace("{address}", $this->model->customer->addresses[0]->name, $invoiceTemplate);
            } else {
                $invoiceTemplate = Str::replace("{address}", "", $invoiceTemplate);
            }
        }

        if (strpos($invoiceTemplate, '{tin}')) {
            $invoiceTemplate = Str::replace("{tin}", $this->model->customer->tin, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{note}')) {
            $invoiceTemplate = Str::replace("{note}", $this->model->note, $invoiceTemplate);
        }


        if (strpos($invoiceTemplate, '{item_details}')) {
            $orderItems = $this->model->returnOrderProducts;
            $orderItemArray = '';
            foreach ($orderItems as $item) {
                $orderItemArray .= '
                <tr>
                    <td>' . $item->variant->name . '</td>
                    <td>' . $item->quantity . '</td>
                    <td>' . $item->discount . '</td>  
                    <td>' . $item->sub_total . '</td>
                </tr>
            ';

            }
            $invoiceTemplate = Str::replace("{item_details}", $orderItemArray, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{sub_total}')) {
            $invoiceTemplate = Str::replace("{sub_total}", $this->model->sub_total, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{shipment_amount}')) {
            $invoiceTemplate = Str::replace("{shipment_amount}", 0, $invoiceTemplate);
        }


        if (strpos($invoiceTemplate, '{discount}')) {
            $invoiceTemplate = Str::replace("{discount}", $this->model->discount, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{sub_total}')) {
            $invoiceTemplate = Str::replace("{sub_total}", $this->model->sub_total, $invoiceTemplate);
        }


        if (strpos($invoiceTemplate, '{barcode}')) {
            $barcode = (new \Milon\Barcode\DNS1D)->getBarcodeHTML("{$this->model->last_invoice_number}", "I25+");;
            $invoiceTemplate = Str::replace("{barcode}", $barcode, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{qrcode}')) {
            $qrcode = (new \Milon\Barcode\DNS2D)->getBarcodeHTML("{$this->model->invoice_number}", "QRCODE");;
            $invoiceTemplate = Str::replace("{qrcode}", $qrcode, $invoiceTemplate);
        }


        return [
            'order' => $this->model,
            'invoice_template' => $invoiceTemplate
        ];
    }

}