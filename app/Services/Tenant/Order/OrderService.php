<?php


namespace App\Services\Tenant\Order;

use App\Helpers\Traits\SmsHelper;
use App\Jobs\Invoice\InvoiceMailJob;
use App\Models\Core\Setting\Setting;
use App\Models\Pos\Inventory\Stock\Stock;
use App\Models\Tenant\InvoiceTemplate\InvoiceTemplate;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Customer\CustomerLedger;
use App\Models\Tenant\Customer\CustomerTransaction;
use App\Models\Tenant\Sales\Cash\CashRegister;
use App\Models\Tenant\Sales\Cash\CashRegisterLog;
use App\Models\Tenant\SmsTemplate\SmsTemplate;
use App\Repositories\Core\Setting\SettingRepository;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\TenantService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PDF;

class OrderService extends TenantService
{
    use SmsHelper;

    public $lastInvoiceNumber;
    public $invoiceNumber;
    protected $setting;

    public function __construct(Order $order, Setting $setting)
    {
        $this->model = $order;
        $this->setting = $setting;
    }


    public function maxMinPriceAmount(): array
    {
        $maxCharge = $this->model->query()->branchOrWarehouse(request('branch_or_warehouse_id'))->max('grand_total');
        $minCharge = $this->model->query()->branchOrWarehouse(request('branch_or_warehouse_id'))->min('grand_total');

        return [
            'maxRange' => $maxCharge,
            'minRange' => $minCharge
        ];
    }

    public function holdOrders()
    {
        return $this->model->query()
            ->where([
                'cash_register_id' => request('cash_register_id'),
                'branch_or_warehouse_id' => request('branch_or_warehouse_id'),
                'created_by' => auth()->id(),
                'status_id' => resolve(StatusRepository::class)->orderHold()
            ])
            ->with([
                'orderProducts',
                'orderProducts.stock:id,variant_id',
                'orderProducts.stock.variant:id,name,product_id,selling_price,upc',
            ])
            ->get();
    }

    public function holdOrderDelete()
    {
        $holdOrders = $this->model->query()
            ->whereIn('id', $this->getAttribute('orderIds'))
            ->where([
                'created_by' => $this->getAttribute('soldBy'),
                'status_id' => resolve(StatusRepository::class)->orderHold()
            ])->get();

        foreach ($holdOrders as $order) {
            foreach ($order->orderProducts as $product) {
                $this->updateStockQuantity(
                    $order->branch_or_warehouse_id,
                    $product->stock_id,
                    $product->variant_id,
                    $product->quantity,
                    'hold_order_delete'
                );
                $product->delete();
            }
            $order->delete();
        }
        return $this;
    }

    public function storeOrder(): static
    {
        Log::info('Entering storeOrder method');

        // Determine status
        if ($this->getAttribute('is_being_held')) {
            $statusId = resolve(StatusRepository::class)->orderHold();
        } else {
            $statusId = ($this->getAttribute('due_amount') ?? 0) > 0
                ? resolve(StatusRepository::class)->orderDue()
                : resolve(StatusRepository::class)->orderDone();
        }

        // ── Create or update order ────────────────────────────────────────
        if ($this->getAttribute('id')) {
            // Updating existing hold order
            $order = $this->model->query()->find($this->getAttribute('id'));
            if ($order && $order->status_id === resolve(StatusRepository::class)->orderHold()) {
                $order->orderProducts()->delete();

                $order->update(array_merge(
                    $this->getAttributes([
                        'branch_or_warehouse_id',
                        'cash_register_id',
                        'customer_id',
                        'payment_type',
                        'tax_id',
                        'discount_id',
                        'discount_type',
                        'discount_value',
                        'total_tax',
                        'discount',
                        'sub_total',
                        'grand_total',
                        'due_amount',
                        'paid_amount',
                        'change_return',
                        'payment_note',
                        'note'
                    ]),
                    [
                        'ordered_at'   => now(),
                        'created_by'   => auth()->id(),
                        'tenant_id'    => 1,
                        'status_id'    => $statusId,
                    ]
                ));

                $this->setModel($order);
            }
        } else {
            // New order
            $orderInfo = array_merge(
                $this->getAttributes([
                    'branch_or_warehouse_id',
                    'cash_register_id',
                    'customer_id',
                    'payment_type',
                    'total_tax',
                    'tax_id',
                    'discount_id',
                    'discount_type',
                    'discount_value',
                    'discount',
                    'sub_total',
                    'grand_total',
                    'due_amount',
                    'paid_amount',
                    'change_return',
                    'payment_note',
                    'note',
                    'ordered_at'
                ]),
                [
                    'tenant_id'           => 1,
                    'created_by'          => auth()->id(),
                    'invoice_number'      => $this->invoiceNumber,
                    'last_invoice_number' => $this->lastInvoiceNumber,
                    'status_id'           => $statusId,
                ]
            );

            $this->model->fill($orderInfo)->save();
        }

        $order = $this->model;

        $customer = $order->customer;

        if (!$customer || $customer->id === 1) {
            return $this;
        }

        $grandTotal   = (float) $order->grand_total;
        $paidThisTime = (float) ($this->getAttribute('paid_amount') ?? 0);
        $dueThisTime  = $grandTotal - $paidThisTime;

        Log::info('Order financials', [
            'grand_total'   => $grandTotal,
            'paid_this_time' => $paidThisTime,
            'due_this_time' => $dueThisTime,
            'order_id'      => $order->id,
        ]);

        CustomerLedger::create([
            'customer_id'     => $customer->id,
            'type'            => 'sale',
            'direction'       => 'debit',
            'amount'          => $grandTotal,
            'description'     => "Sale #{$order->invoice_number}",
            'reference_type'  => get_class($order),
            'reference_id'    => $order->id,
            'created_by'      => auth()->id(),
        ]);

        if ($paidThisTime > 0) {
            $transactions = $this->getAttribute('transactions') ?? [];

            foreach ($transactions as $transaction) {
                $amount = (float) ($transaction['amount'] ?? 0);
                if ($amount <= 0) continue;

                $ledgerPayment = CustomerLedger::create([
                    'customer_id'     => $customer->id,
                    'type'            => 'payment',
                    'direction'       => 'credit',
                    'amount'          => $amount,
                    'description'     => "Payment on sale #{$order->invoice_number}",
                    'reference_type'  => get_class($order),
                    'reference_id'    => $order->id,
                    'created_by'      => auth()->id(),
                ]);

                CustomerTransaction::create([
                    'customer_id'        => $customer->id,
                    'payment_method_id'  => $transaction['payment_method_id'] ?? null,
                    'cash_register_id'   => $this->getAttribute('cash_register_id'),
                    'created_by'         => auth()->id(),
                    'amount'             => $amount,
                    'type'               => 'payment',
                    'note'               => $this->getAttribute('payment_note') ?? null,
                    'customer_ledger_id' => $ledgerPayment->id,
                ]);
            }
        }

        if ($dueThisTime > 0) {
            $customer->debit($dueThisTime);
        }

        //   if ($dueThisTime > 0) {
        //     $customer->debit($dueThisTime);
        // } elseif ($paidThisTime > $grandTotal) {
        //     $overpayment = $paidThisTime - $grandTotal;
        //     $customer->credit($overpayment);
        // }

        return $this;
    }

    public function receiveDuePayment(array $data): static
    {
        $amount = $data['amount'];
        $order = $this->model;

        // 1. Update order (legacy support - optional)
        $order->increment('paid_amount', $amount);
        $order->decrement('due_amount', $amount);

        if ($order->paid_amount >= $order->grand_total) {
            $order->status_id = resolve(StatusRepository::class)->orderDone();
        }
        $order->save();

        $customer = $order->customer;

        $ledgerEntry = CustomerLedger::create([
            'customer_id'     => $customer->id,
            'type'            => 'payment',
            'direction'       => 'credit',
            'amount'          => $amount,
            'description'     => "Due payment - Invoice #{$order->invoice_number}",
            'reference_type'  => Order::class,
            'reference_id'    => $order->id,
            'created_by'      => auth()->id(),
        ]);

        CustomerTransaction::create([
            'customer_id'        => $customer->id,
            'payment_method_id'  => $data['payment_method_id'],
            'cash_register_id'   => $this->getAttribute('cash_register_id') ?? null,
            'created_by'         => auth()->id(),
            'amount'             => $amount,
            'type'               => 'payment',
            'note'               => $data['note'] ?? null,
            'customer_ledger_id' => $ledgerEntry->id,
        ]);

        // 3. Update balance
        $customer->credit($amount); // reduce debt

        return $this;
    }

    private function orderProductRequest($orderProduct): array
    {

        $this->updateStockQuantity(
            $this->getAttribute('branch_or_warehouse_id'),
            $orderProduct['stock_id'],
            $orderProduct['variant_id'],
            $orderProduct['quantity'],
            'sales'
        );
        return [
            'order_id' => $this->model->id,
            'stock_id' => $orderProduct['stock_id'],
            'branch_or_warehouse_id' => $this->getAttribute('branch_or_warehouse_id'),
            'order_product_id' => $orderProduct['order_product_id'],
            'variant_id' => $orderProduct['variant_id'],
            'price' => $orderProduct['price'],
            'ordered_at' => Carbon::now(),
            'selling_price' => $orderProduct['selling_price'],
            'avg_purchase_price' => $orderProduct['avg_purchase_price'],
            'quantity' => $orderProduct['quantity'],
            'tax_amount' => $orderProduct['tax_amount'],
            'discount_type' => $orderProduct['discount_type'],
            'discount_value' => $orderProduct['discount_value'],
            'discount_amount' => $orderProduct['discount_amount'],
            'sub_total' => $orderProduct['sub_total'],
            'note' => $orderProduct['note'],
            'tenant_id' => 1
        ];
    }

    public function storeOrderProduct(): static
    {
        $orderProducts = [];

        if (request()->order_products) {
            foreach (request()->order_products as $orderProduct) {
                $orderProducts[] = $this->orderProductRequest($orderProduct);
            }
        }

        $this->model
            ->orderProducts()
            ->insert($orderProducts);

        return $this;
    }

    public function updateStockQuantity($branchId, $stockId, $variantId, $quantity, $orderStatus): static
    {
        $stock = Stock::query()
            ->where([
                'id' => $stockId,
                'branch_or_warehouse_id' => $branchId,
                'variant_id' => $variantId
            ])->first();
        if ($stock) {
            if ($orderStatus === 'sales') {
                $stock->decrement('available_qty', $quantity);
                $stock->increment('total_sales_qty', $quantity);
            } else {
                $stock->increment('available_qty', $quantity);
                $stock->decrement('total_sales_qty', $quantity);
            }
            $stock->save();
        }
        return $this;
    }

    public function transactionRequest($transaction)
    {

        $this->cashCounterLog($transaction);
        return [
            'payment_method_id' => $transaction['payment_method_id'],
            'created_by' => 1,
            'transaction_at' => Carbon::now(),
            'transactionable_type' => get_class($this->model),
            'transactionable_id' => $this->model->id,
            'amount' => $transaction['amount'],
            'tenant_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function makeTransactions(): static
    {
        $transactions = [];
        if ($this->getAttr('transactions')) {
            foreach ($this->getAttr('transactions') as $key => $transaction) {
                $transactions[] = $this->transactionRequest($transaction);
            }
        }

        $this->model
            ->transactions()
            ->insert($transactions);

        return $this;
    }


    public function cashRegisterLog() {}

    public function generateInvoiceId()
    {
        Log::info('generateInvoiceId');

        $lastOrder = Order::query()
            ->select('last_invoice_number')
            ->where('branch_or_warehouse_id', request()->branch_or_warehouse_id)
            ->orderBy('id', 'desc')
            ->first();


        $prefix = $this->setting->query()
            ->where('name', 'sales_invoice_prefix')
            ->first();
        $prefix = $prefix ? $prefix->value . '-' : '';

        $suffix = $this->setting->query()
            ->where('name', 'sales_invoice_suffix')
            ->first();
        $suffix = $suffix ? '-' . $suffix->value : '';


        if ($lastOrder) {
            $this->invoiceNumber = $prefix . ++$lastOrder->last_invoice_number . $suffix;
            $this->lastInvoiceNumber = $lastOrder->last_invoice_number;
        } else {
            $invoiceStartFrom = $this->setting->query()
                ->where('name', 'sales_invoice_starts_from')
                ->first();

            $this->invoiceNumber = $prefix . $invoiceStartFrom->value . $suffix;

            $this->lastInvoiceNumber = $invoiceStartFrom->value;
        }
        return $this;
    }


    public function sendInvoice(): static
    {
        $checkAutoInvoiceSend = $this->setting->query()->where('name', 'sales_invoice_auto_email')->first();

        if ($checkAutoInvoiceSend->value)
            dispatch(new InvoiceMailJob($this->model));

        return $this;
    }

    public function sendAutoSms(): static
    {
        try {
            //To check sending auto sms is active
            $sendAutoSms = resolve(SettingRepository::class)->findAppSettingWithName('send_auto_sms');

            if ($sendAutoSms->value) {
                $smsTemplate = SmsTemplate::query()->where('is_default', 1)->first();

                if ($smsTemplate) {
                    $variable = ['{first_name}', '{company_name}', '{invoice_id}', '{total}'];
                    $with_replace = [$this->model->customer->first_name ?? '', settings('company_name', 'Salex') ?? '', $this->model->invoice_number ?? '', $this->model->grand_total ?? ''];
                    $content = str_replace($variable, $with_replace, $smsTemplate->content);
                    $this->sendSms($this->model->customer->phoneNumbers[0]->value, $content) ?? '';
                } else {
                    $message = "Dear {$this->model->customer->first_name}, your invoice number is {$this->model->invoice_number}. You have purchase {$this->model->grand_total}" . __t('thanks_for_shopping_from_us_please_come_again');
                    $this->sendSms($this->model->customer->phoneNumbers[0]->value, $message) ?? '';
                }
            }
            return $this;
        } catch (\Exception $exception) {
            return $this;
        }
    }

    public function setDueCollectValidator(): static
    {
        validator(request()->all(), [
            'due_amount' => 'required|numeric|min:0'
        ])->validate();

        return $this;
    }

    public function duePaymentReceive(): static
    {

        DB::transaction(function () {

            $amount = (float) $this->getAttribute('due_amount');
            $order  = $this->model;

            $this->updateOrderAmounts($order, $amount);
            $ledger = $this->createCustomerLedger($order, $amount);
            $this->createCustomerTransaction($order, $amount, $ledger);
            $this->updateCustomerBalance($order->customer, $amount);
        });

        return $this;
    }

    private function updateOrderAmounts(Order $order, float $amount): void
    {
        $order->increment('paid_amount', $amount);
        $order->decrement('due_amount', $amount);

        $order->status_id = $order->paid_amount >= $order->grand_total
            ? resolve(StatusRepository::class)->orderDone()
            : resolve(StatusRepository::class)->orderDue();

        $order->save();
    }
    private function createCustomerLedger(Order $order, float $amount): CustomerLedger
    {
        return CustomerLedger::create([
            'customer_id'    => $order->customer_id,
            'type'           => 'payment',
            'direction'      => 'credit',
            'amount'         => $amount,
            'description'    => "Due payment received - Invoice #{$order->invoice_number}",
            'reference_type' => Order::class,
            'reference_id'   => $order->id,
            'created_by'     => auth()->id(),
        ]);
    }

    private function createCustomerTransaction(
        Order $order,
        float $amount,
        CustomerLedger $ledger
    ): void {
        CustomerTransaction::create([
            'customer_id'        => $order->customer_id,
            'payment_method_id'  => $this->getAttribute('payment_method_id'),
            'cash_register_id'   => $this->getAttribute('cash_register_id'),
            'created_by'         => auth()->id(),
            'amount'             => $amount,
            'type'               => 'payment',
            'note'               => $this->getAttribute('note'),
            'customer_ledger_id' => $ledger->id,
        ]);
    }
    private function updateCustomerBalance($customer, float $amount): void
    {
        // credit means reduce due
        $customer->credit($amount);
    }


    public function cashCounterLog($transaction): static
    {
        $cashCounter = CashRegister::query()->find($this->getAttribute('cash_register_id'));

        $cashCounterLog = new CashRegisterLog;
        $cashCounterLog->cash_register_id = $cashCounter->id;
        $cashCounterLog->branch_or_warehouse_id = $cashCounter->branch_or_warehouse_id;
        $cashCounterLog->order_id = $this->model->id;
        $cashCounterLog->user_id = auth()->id();
        $cashCounterLog->opened_by = $cashCounter->opened_by;
        $cashCounterLog->closed_by = auth()->id();
        $cashCounterLog->payment_method_id = $transaction['payment_method_id'];
        $cashCounterLog->status_id = $cashCounter->status_id;
        $cashCounterLog->opening_balance = $cashCounter->opening_balance;
        $cashCounterLog->cash_sales = $transaction['amount'];
        $cashCounterLog->cash_receives = 0;
        $cashCounterLog->difference = 0;
        $cashCounterLog->is_running = true;
        $cashCounterLog->opening_time = $cashCounter->opening_time;
        $cashCounterLog->note = $this->getAttribute('note');
        $cashCounterLog->save();

        return $this;
    }

    public function generateSalesInvoiceTemplate()
    {
        $template = InvoiceTemplate::where([
            'is_default' => 1,
            'type' => 'sales_invoice'
        ])->first();


        $invoiceTemplate = $template->custom_content ? $template->custom_content : $template->default_content;

        if (strpos($invoiceTemplate, '{logo_source}') > -1) {
            $invoiceLogo = settings('sales_invoice_logo', null);
            $logo = $invoiceLogo != null ? asset($invoiceLogo) : \App\Http\Composer\Helper\LogoIcon::new(true)->logoIcon()['logo'];
            $invoiceTemplate = Str::replace("{logo_source}", $logo, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{company_logo}') > -1) {
            $company_logo = settings('company_logo', null);
            $logo = $company_logo != null ? asset($company_logo) : \App\Http\Composer\Helper\LogoIcon::new(true)->logoIcon()['logo'];
            $invoiceTemplate = Str::replace('{company_logo}', "<div class='tharmal-invoice__item tharmal-invoice__item--header'><div><img class='logo' src='$logo' alt='logo' /></div></div>", $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{sales_invoice_logo}') > -1) {
            $sales_invoice_logo = settings('sales_invoice_logo', null);
            $logo = $sales_invoice_logo != null ? asset($sales_invoice_logo) : \App\Http\Composer\Helper\LogoIcon::new(true)->logoIcon()['logo'];
            $invoiceTemplate = Str::replace('{sales_invoice_logo}', "<div class='tharmal-invoice__item tharmal-invoice__item--header'><div><img class='logo' src='$logo' alt='logo' /></div></div>", $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{return_invoice_logo}') > -1) {
            $return_invoice_logo = settings('return_invoice_logo', null);
            $logo = $return_invoice_logo != null ? asset($return_invoice_logo) : \App\Http\Composer\Helper\LogoIcon::new(true)->logoIcon()['logo'];
            $invoiceTemplate = Str::replace('{return_invoice_logo}', "<img src='$logo' alt='company logo' />", $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{company_name}') > -1) {
            $invoiceTemplate = Str::replace("{company_name}", settings('company_name', ''), $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{branch_phone}') > -1) {
            $invoiceTemplate = Str::replace("{branch_phone}", $this->model->branchOrWarehouse->phone, $invoiceTemplate);
        }
        if (strpos($invoiceTemplate, '{branch_email}') > -1) {
            $invoiceTemplate = Str::replace("{branch_email}", $this->model->branchOrWarehouse->email, $invoiceTemplate);
        }
        if (strpos($invoiceTemplate, '{company_address}') > -1) {
            $invoiceTemplate = Str::replace("{company_address}", $this->model->branchOrWarehouse->location, $invoiceTemplate);
        }
        if (strpos($invoiceTemplate, '{sale_note}') > -1) {
            $invoiceTemplate = Str::replace("{sale_note}", $this->model->note, $invoiceTemplate);
        }
        if (strpos($invoiceTemplate, '{payment_note}') > -1) {
            $invoiceTemplate = Str::replace("{payment_note}", $this->model->payment_note, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{company_name}') > -1) {
            $invoiceTemplate = Str::replace("{company_name}", settings('company_name', ''), $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{date}') > -1) {
            $datetime_format = "Y-m-d H:i";
            $date_format = settings('date_format') ?? 'Y-m-d';
            $format = settings('time_format') ?? 'H';
            $time_format = $format === 'h' ? 'h:i A' : 'H:i';
            $datetime_format = "$date_format $time_format";
            $date = Carbon::parse($this->model->ordered_at)->format($datetime_format);
            $invoiceTemplate = Str::replace("{date}", $date, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{time}') > -1) {
            $time = Carbon::parse($this->model->ordered_at)->toTimeString();
            $invoiceTemplate = Str::replace("{time}", $time, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{invoice_number}') > -1) {
            $invoiceTemplate = Str::replace("{invoice_number}", $this->model->invoice_number, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{cash_counter}') > -1) {
            $invoiceTemplate = Str::replace("{cash_counter}", $this->model->cashRegister->name, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{customer_name}') > -1) {
            $invoiceTemplate = Str::replace("{customer_name}", $this->model->customer->full_name ?? '', $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{employee_name}') > -1) {
            $invoiceTemplate = Str::replace("{employee_name}", $this->model->createdBy->full_name ?? '', $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{phone_number}') > -1) {
            if ($this->model->customer->phoneNumbers->count() > 0) {
                $invoiceTemplate = Str::replace("{phone_number}", $this->model->customer->phoneNumbers[0]->value ?? '', $invoiceTemplate);
            } else {
                $invoiceTemplate = Str::replace("{phone_number}", "", $invoiceTemplate);
            }
        }
        if (strpos($invoiceTemplate, '{address}') > -1) {
            if ($this->model->customer->addresses->count() > 0) {
                $invoiceTemplate = Str::replace("{address}", $this->model->customer->addresses[0]->name ?? '', $invoiceTemplate);
            } else {
                $invoiceTemplate = Str::replace("{address}", "", $invoiceTemplate);
            }
        }

        if (strpos($invoiceTemplate, '{tin}') > -1) {
            $invoiceTemplate = Str::replace("{tin}", $this->model->customer->tin ?? '', $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{note}') > -1) {
            $invoiceTemplate = Str::replace("{note}", $this->model->note, $invoiceTemplate);
        }


        if (strpos($invoiceTemplate, '{item_details}') > -1) {
            $orderItems = $this->model->orderProducts;
            $orderItemArray = '';
            foreach ($orderItems as $item) {
                $orderItemArray .= '
                    <tr>
                        <td>
                            <div>' . $item->variant->name . '</div> 
                            <div>' . $item->discount . '</div>  
                        </td>
                        <td>
                            <div>' . $item->quantity . '</div> 
                        </td>
                        <td>
                            <div>' . $item->price .  '</div>
                        </td>
                        <td class="text-right">' . number_formatter($item->sub_total) . '</td>
                    </tr>
                ';
            }
            $invoiceTemplate = Str::replace("{item_details}", $orderItemArray, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{sub_total}') > -1) {
            $invoiceTemplate = Str::replace("{sub_total}", number_formatter($this->model->sub_total), $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{shipment_amount}') > -1) {
            $invoiceTemplate = Str::replace("{shipment_amount}", number_formatter(0), $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{tax}') > -1) {
            $this->model->load('tax');
            if (isset($this->model->tax->percentage)) {
                $invoiceTemplate = Str::replace("{tax}", "[ " . $this->model->tax->percentage . " %]", $invoiceTemplate);
            } else {
                $invoiceTemplate = Str::replace("{tax}", "", $invoiceTemplate);
            }
        }

        if (strpos($invoiceTemplate, '{tax_amount}') > -1) {
            $invoiceTemplate = Str::replace("{tax_amount}", number_formatter($this->model->total_tax), $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{discount}') > -1) {
            if ($this->model->discount_type === 'percentage') {
                $invoiceTemplate = Str::replace("{discount}", "[ " . $this->model->discount . " %]", $invoiceTemplate);
            } else {
                $invoiceTemplate = Str::replace("{discount}", "", $invoiceTemplate);
            }
        }

        if (strpos($invoiceTemplate, '{discount_amount}') > -1) {
            $invoiceTemplate = Str::replace("{discount_amount}", number_formatter($this->model->discount_value), $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{paid_amount}') > -1) {
            $invoiceTemplate = Str::replace("{paid_amount}", number_formatter($this->model->paid_amount), $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{due_amount}')) {
            $invoiceTemplate = Str::replace("{due_amount}", number_formatter($this->model->due_amount), $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{payment_details}') > -1) {
            $paymentNames = $this->model->transactions
                ->map(fn($t) => $t->paymentMethod->name . ' (' . number_formatter($t->amount) . ')')
                ->implode(', ');

            $invoiceTemplate = Str::replace(
                "{payment_details}",
                $paymentNames,
                $invoiceTemplate
            );
        }

        if (strpos($invoiceTemplate, '{invoice_id}') > -1) {
            $invoiceTemplate = Str::replace("{invoice_id}", $this->model->invoice_id ?? '', $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{total}') > -1) {
            $invoiceTemplate = Str::replace("{total}", number_formatter($this->model->grand_total), $invoiceTemplate);
        }
        if (strpos($invoiceTemplate, '{change_return}') > -1) {
            $invoiceTemplate = Str::replace("{change_return}", number_formatter($this->model->change_return), $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{grand_total}') > -1) {
            $invoiceTemplate = Str::replace("{grand_total}", number_formatter($this->model->grand_total), $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{barcode}') > -1) {
            $barcode = (new \Milon\Barcode\DNS1D)->getBarcodePNG("{$this->model->last_invoice_number}", "I25+");
            $barcode = '<img src="data:image/png;base64,' . $barcode . '" style="text-align:center">';
            $invoiceTemplate = Str::replace("{barcode}", $barcode, $invoiceTemplate);
        }

        if (strpos($invoiceTemplate, '{qrcode}') > -1) {
            $qrcode = (new \Milon\Barcode\DNS2D)->getBarcodePNG("{$this->model->last_invoice_number}", "QRCODE");
            $qrcode = '<img src="data:image/png;base64,' . $qrcode . '" style="text-align:center">';
            $invoiceTemplate = Str::replace("{qrcode}", $qrcode, $invoiceTemplate);
        }

        return [
            'order' => $this->model,
            'invoice_template' => $invoiceTemplate
        ];
    }
}
