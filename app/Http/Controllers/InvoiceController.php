<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Title;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class InvoiceController extends Controller
{

    public function index()
    {
        return view('livewire.invoices.report.index');
    }
    public function getData(Request $request)
    {

        $departments = Department::query()
            ->select('id', 'is_service', 'name_ar', 'name_en')
            ->where('is_service', true)
            ->get();

        $titles = Title::query()
            ->select('id', 'name_ar', 'name_en')
            ->whereIn('id', Title::TECHNICIANS_GROUP)
            ->get();

        $technicians = User::query()
            ->whereIn('title_id', Title::TECHNICIANS_GROUP)
            ->whereHas('orders_technician', function ($q) use ($request) {
                $q->whereDate('completed_at', '>=', $request->start_date);
                $q->whereDate('completed_at', '<=', $request->end_date);
            })
            ->select('id', 'name_ar', 'name_en', 'title_id', 'department_id')
            ->orderBy('title_id')
            ->orderBy('name_ar')
            ->get();

        $orders = Order::query()
            ->select('id', 'technician_id')
            ->whereDate('completed_at', '>=', $request->start_date)
            ->whereDate('completed_at', '<=', $request->end_date)
            ->get();

        $invoices = Invoice::query()
            ->select('invoices.id', 'invoices.order_id', 'invoices.payment_status', 'invoices.discount', 'invoices.delivery')
            ->join('orders', 'invoices.order_id', '=', 'orders.id')
            ->addSelect('orders.technician_id')
            ->whereDate('invoices.created_at', '>=', $request->start_date)
            ->whereDate('invoices.created_at', '<=', $request->end_date)
            ->whereNull('invoices.deleted_at')
            ->withSum(['invoice_details as servicesAmountSum' => function ($q) {
                $q->whereHas('service', function ($q) {
                    $q->where('type', 'service');
                });
            }], DB::raw('quantity * price'))

            ->withSum(['invoice_details as invoiceDetailsPartsAmountSum' => function ($q) {
                $q->whereHas('service', function ($q) {
                    $q->where('type', 'part');
                });
            }], DB::raw('quantity * price'))

            ->withSum(['invoice_part_details as internalPartsAmountSum' => function ($q) {
                $q->where('type', 'internal');
            }], DB::raw('quantity * price'))

            ->withSum(['invoice_part_details as externalPartsAmountSum' => function ($q) {
                $q->where('type', 'external');
            }], DB::raw('quantity * price'))

            ->withSum(['payments as totalCashAmountSum' => function ($q) {
                $q->where('method', 'cash');
            }], 'amount')

            ->withSum(['payments as totalKnetAmountSum' => function ($q) {
                $q->where('method', 'knet');
            }], 'amount')

            ->get();

        return response()->json([
            'departments' => $departments,
            'technicians' => $technicians,
            'orders' => $orders,
            'invoices' => $invoices,
            'titles' => $titles,
        ]);
    }

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
