<?php

namespace App\Mail\Invoice;

use App\Mail\Tag\InvoiceTag;
use App\Models\Core\Notification\NotificationTemplate;
use App\Models\Tenant\Order\Order;
use App\Notifications\Core\Helper\NotificationTemplateHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class InvoiceMail extends Mailable implements ShouldQueue

{
    use Queueable, SerializesModels;

    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;

        $tag = new InvoiceTag($this->order);

        $this->subject = optional($this->template())->parseSubject(
            method_exists($tag, 'invoiceSendSubject') ? $tag->invoiceSendSubject() : ['{invoice_number}' => optional($order)->invoice_number]
        );
    }

    public function build(): InvoiceMail
    {
        $invoiceSetting = resolve(\App\Http\Controllers\Pos\Api\Settings\InvoiceSettingController::class)->getInvoiceSetting();
        $order = $this->order;
        $barcode = (new \Milon\Barcode\DNS1D)->getBarcodeHTML("{$order->last_invoice_number}", "I25+");
        $qrcode = (new \Milon\Barcode\DNS2D)->getBarcodeHTML("{$order->last_invoice_number}", "QRCODE",3,3);

        $order['barcode'] = $barcode;
        $order['qrcode'] = $qrcode;

        $pdf = PDF::loadView('pdf.invoice_pdf', compact('order', 'invoiceSetting'));

        $output = $pdf->output();
        $fileName = "{$this->order->invoice_number}.pdf";

        //tag initiate here
        $template = $this->template();
        $tag = new InvoiceTag($this->order);

        return $this->view('notification.mail.invoice.index', [
            'template' => $template->parse(
                $tag->invoiceContent()
            )
        ])->subject($this->subject)
            ->attachData($output, $fileName);
    }

    public function template(): NotificationTemplate
    {
        return NotificationTemplateHelper::new()
            ->on('invoice_sent')
            ->mail();
    }
}
