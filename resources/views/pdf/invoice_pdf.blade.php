<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        p {
            font-size: .7em;
            line-height: 1.2em;
        }

        .tharmal-invoice {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 58mm;
            margin: auto;
        }

        .tharmal-invoice__item {
            display: flex;
            flex-direction: column;
            margin: 10px 0;
        }

        .tharmal-invoice__item--header {
            text-align: center;
        }

        .tharmal-invoice__item--body {
        }

        .tharmal-invoice__item--body table {
            border-collapse: collapse;
            font-size: .7em;
            width: 100%;
        }

        .tharmal-invoice__item--body table th {
            padding: 5px 0;
        }

        .tharmal-invoice__item--body table td {
            font-size: smaller;
            padding: 5px 0 10px 0;
            vertical-align: top
        }

        .tharmal-invoice__item--footer {
            text-align: center;
            gap: 10px;
        }

        .tharmal-invoice__item--footer .barcode {
            width: 75%;
            height: 48px;
        }

        .tharmal-invoice__item--footer .qrcode {
            width: 35%;
        }

        .tharmal-invoice__item .logo {
            width: 72px;
            margin-bottom: 10px;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

<!-- header -->
<div class="tharmal-invoice">
    <div class="tharmal-invoice__item tharmal-invoice__item--header">
        <div>
            <img class="logo" src="{{ url(@$invoiceSetting['sales_invoice_logo']) }}"
                 alt="logo">
        </div>
        <p>Address: {{ $order->branchOrWarehouse->name }} </p>
        <p>Phone: {{ $order->branchOrWarehouse->phone }} </p>
        <p>Email: {{ $order->branchOrWarehouse->email }} </p>
    </div>

    <div class="tharmal-invoice__item">
        <p>Date: {{ $order->ordered_at }}</p>
        <p>Cash Counter: {{ $order->cashRegister->name ?? '' }}</p>
        <p>Customer: {{ $order->customer->full_name }}</p>
        <p>Invoice ID: {{ $order->invoice_number }}</p>
        @if($order->note)
            <p>Sale Note: {{ $order->note }}</p>
        @endif
        @if($order->payment_note)
            <p>Payment Note: {{ $order->payment_note }}</p>
        @endif
    </div>

    <!-- body -->
    <div class="tharmal-invoice__item tharmal-invoice__item--body">
        <table>
            <tbody>
            <tr style="border-bottom: 2px dotted #000;">
                <th class="text-left">Prod</th>
                <th class="text-right" style="width: 1%;">S.total</th>
            </tr>
            @foreach($order->orderProducts as $key => $item)
                <tr>
                    <td>
                        <div>{{ $key+1 }} {{ $item->variant->name ?? '' }}</div>
                        <div>{{ $item->quantity }}{{ $item->unit->name?? '' }}
                            X {{ ($item->price-($item->discount_value))+$item->tax_amount }}</div>
                    </td>
                    <td class="text-right">{{ $item->sub_total }}</td>
                </tr>
            @endforeach
            <tr style="border-top: 2px dotted #000;">
                <td style="padding: 10px 0 0 0;">Sub-total</td>
                <td class="text-right" style="padding: 10px 0 0 0;">{{ $order->sub_total }}</td>
            </tr>
            <tr>
                <td style="padding: 0;">
                    Discount on subtotal
                    @if($order->discount_type === 'percentage')
                        ({{ $order->discount }}%)
                    @endif
                </td>
                <td class="text-right" style="padding: 0;">{{ $order->discount_value }}</td>
            </tr>
            <tr>
                <td style="padding: 0 0 10px 0;">
                    Tax
                    @if($order->tax->type === 'single_tax')
                        ({{ $order->tax->percentage }}%)
                    @endif
                </td>
                <td class="text-right" style="padding: 0 0 10px 0;">{{ $order->total_tax }}</td>
            </tr>
            <tr style="border-top: 2px dotted #000;">
                <th class="text-left">Grand Total</th>
                <th class="text-right">{{ $order->sub_total }}</th>
            </tr>
            <tr>
                <td style="padding: 0;">Paid amount</td>
                <td class="text-right" style="padding: 0;">{{ $order->paid_amount }}</td>
            </tr>
            <tr>
                <td style="padding: 0;">Change return</td>
                <td class="text-right" style="padding: 0;">{{ $order->exchange_amount }}</td>
            </tr>
            <tr style="border-bottom: 2px dotted #000;">
                <td style="padding: 0 0 10px 0;">Due</td>
                <td class="text-right" style="padding: 0 0 10px 0;">{{ $order->due_amount }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div>

    </div>
    <div class="tharmal-invoice__item tharmal-invoice__item--footer">
        <div style="width: 50%;  margin: 0 33%;">
            <span>{!! $order->barcode !!}</span><br>
            <span>{!! $order->qrcode !!}</span>
        </div>
    </div>
    <div class="tharmal-invoice__item tharmal-invoice__item--footer">
        <div>
            <p>Thank you for shopping with us</p>
            <p>Please come again</p>
        </div>
    </div>
</div>

</body>
</html>