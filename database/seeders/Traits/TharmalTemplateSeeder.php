<?php

namespace Database\Seeders\Traits;

use App\Models\Tenant\InvoiceTemplate\InvoiceTemplate;

trait TharmalTemplateSeeder
{
    public function tharmalInvoices(): array
    {
        $invoice = '<div class="tharmal-invoice"><div class="tharmal-invoice__item tharmal-invoice__item--header"><div><img class="logo" src="{logo_source}" alt="logo" /></div><p>Address:{company_address}</p><p>Phone: {branch_phone}</p><p>Email: {branch_email}</p></div><div class="tharmal-invoice__item"><p>Date:{date}</p><p>Cash Counter:{cash_counter}</p><p>Customer:{customer_name}</p><p>Invoice ID:{invoice_number}</p><p>Sale Note: {sale_note}</p><p>Payment Note: {payment_note}</p></div><div class="tharmal-invoice__item tharmal-invoice__item--body"><table><tbody><tr style="border-bottom:2px dotted #000"><th class="text-left">Products</th><td class="text-left"><b>Quantity</b></td><td class="text-left"><b>Price</b></td><th class="text-right" style="width:1%">S.total</th></tr><tr><td>{item_details}</td><td><br /></td><td><br /></td></tr><tr style="border-top:2px dotted #000"><td style="padding:10px 0 0 0">Sub-total</td><td style="padding:10px 0 0 0"><br /></td><td style="padding:10px 0 0 0"><br /></td><td class="text-right" style="padding:10px 0 0 0">{sub_total}</td></tr><tr><td style="padding:0">Discount on subtotal {discount}</td><td style="padding:0"><br /></td><td style="padding:0"><br /></td><td class="text-right" style="padding:0">{discount_amount}</td></tr><tr><td style="padding:0 0 10px 0">Tax {tax}</td><td style="padding:0 0 10px 0"><br /></td><td style="padding:0 0 10px 0"><br /></td><td class="text-right" style="padding:0 0 10px 0">{tax_amount}</td></tr><tr style="border-top:2px dotted #000"><th class="text-left">Grand Total</th><td class="text-left"><br /></td><td class="text-left"><br /></td><th class="text-right">{total}</th></tr><tr><td style="padding:0">Paid</td><td style="padding:0"><br /></td><td style="padding:0"><br /></td><td class="text-right" style="padding:0">{paid_amount}</td></tr><tr><td style="padding:0">Change return</td><td style="padding:0"><br /></td><td style="padding:0"><br /></td><td class="text-right" style="padding:0">{change_return}</td></tr><tr style="border-bottom:2px dotted #000"><td style="padding:0 0 10px 0">Due</td><td style="padding:0 0 10px 0"><br /></td><td style="padding:0 0 10px 0"><br /></td><td class="text-right" style="padding:0 0 10px 0">{due_amount}</td></tr></tbody></table></div><div class="tharmal-invoice__item tharmal-invoice__item--footer"><div><p>Thank you for shopping with us</p><p>Please come again</p></div></div></div><div><span class="barcode">{barcode}</span></div><div><span class="qrcode">{qrcode}</span></div>';
        $returnInvoice = '<div class="tharmal-invoice"><div class="tharmal-invoice__item tharmal-invoice__item--header"><div><img class="logo" src="{logo_source}" alt="logo"></div><p>Address:{company_address}</p><p>Phone: {branch_phone}</p><p>Email: {branch_email}</p></div><div class="tharmal-invoice__item"><p>Date:{date}</p><p>Cash Counter:{cash_counter}</p><p>Customer:{customer_name}</p><p>Invoice ID:{invoice_number}</p><p>Sale Note: {sale_note}</p><p>Payment Note: {payment_note}</p></div><div class="tharmal-invoice__item tharmal-invoice__item--body"><table><tbody><tr style="border-bottom:2px dotted #000"><th class="text-left">Products</th><th class="text-right" style="width:1%">S.total</th></tr><tr><td>{item_details}</td></tr><tr><td style="padding:0">Discount on subtotal</td><td class="text-right" style="padding:0">{discount}</td></tr><tr style="border-top:2px dotted #000"><td style="padding:0 0 10px 0"><b>Grand total</b></td><th class="text-right">{sub_total}</th></tr></tbody></table></div><div class="tharmal-invoice__item tharmal-invoice__item--footer"><div><p>Thank you for shopping with us</p><p>Please come again</p></div></div></div><div><span class="barcode">{barcode}</span></div><div><span class="qrcode">{qrcode}</span></div>';
        return [
            [
                'name' => 'Sales Invoice',
                'default_content' => $invoice,
                'custom_content' => $invoice,
                'type' => 'sales_invoice',
                'is_default' => 1,
                'created_by' => 1,
                'tenant_id' => 1
            ],
            [
                'name' => 'Return Invoice',
                'default_content' => $returnInvoice,
                'custom_content' => $returnInvoice,
                'type' => 'return_invoice',
                'is_default' => 1,
                'created_by' => 1,
                'tenant_id' => 1
            ]
        ];
    }
}
