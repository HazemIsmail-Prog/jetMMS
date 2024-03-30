<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Mpdf\Mpdf;

class InvoiceController extends Controller
{
    public function pdf($encryptedOrderId)
    {
        $invoice = Invoice::find(decrypt($encryptedOrderId));
        $invoice->load('invoice_details.service');
        $page_title = 'MD Invoice No.' . $invoice->id;
        $file_name = $page_title . '.pdf';

        $mpdf = new Mpdf();
        $mpdf->showImageErrors = true;
        $body = view('livewire.invoices.pdf.pdf', compact('invoice', 'page_title'));
        $footer = view('livewire.invoices.pdf.footer');
        $mpdf->SetHTMLFooter($footer);
        $mpdf->WriteHTML($body); //should be before output directly
        $mpdf->Output($file_name, 'I');
    }
    public function detailed_pdf($encryptedOrderId)
    {
        $invoice = Invoice::find(decrypt($encryptedOrderId));
        $invoice->load('invoice_details.service');
        $page_title = 'MD Detailed Invoice No.' . $invoice->id;
        $file_name = $page_title . '.pdf';

        $mpdf = new Mpdf();
        $mpdf->showImageErrors = true;
        $body = view('livewire.invoices.pdf.detailed_pdf', compact('invoice', 'page_title'));
        $footer = view('livewire.invoices.pdf.footer');
        $mpdf->SetHTMLFooter($footer);
        // ini_set("pcre.backtrack_limit", "5000000");
        $mpdf->WriteHTML($body); //should be before output directly
        $mpdf->Output($file_name, 'I');
        // $mpdf->Output(storage_path('app/public/invoices/' . $file_name), 'F'); //use this when send to whatsapp
        // 'D': download the PDF file
        // 'I': serves in-line to the browser
        // 'S': returns the PDF document as a string
        // 'F': save as file $file_out

    }
}
