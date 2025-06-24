<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Models\Title;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Http\Resources\InvoiceResource;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesExport;
use App\Services\ActionsLog;
use Mpdf\Mpdf;

class InvoiceController extends Controller
{

    public function index(Request $request)
    {

        // check if the user has the permission to view invoices
        abort_if(!auth()->user()->hasPermission('invoices_menu'), 403, 'Unauthorized action.');

        if($request->wantsJson()) {
            // check if the user has the permission to view invoices
            if(!auth()->user()->hasPermission('invoices_menu')) {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }

            $invoices = Invoice::query()
                ->with('order.customer')
                ->with('order.phone')
                ->with('invoice_details')
                ->with('invoice_part_details')
                ->with('payments')
                ->when($request->invoice_number, fn($query) => 
                    $query->where('id', 'like', '%' . $request->invoice_number . '%')
                )
                ->when($request->order_number, fn($query) => 
                    $query->where('order_id', 'like', '%' . $request->order_number . '%')
                )
                ->when($request->department_ids, fn($query) => 
                    $query->whereHas('order', fn($q) => 
                        $q->whereIn('department_id', $request->department_ids)
                    )
                )
                ->when($request->technician_ids, fn($query) => 
                    $query->whereHas('order', fn($q) => 
                        $q->whereIn('technician_id', $request->technician_ids)
                    )
                )
                ->when($request->customer_name, fn($query) => 
                    $query->whereHas('order.customer', fn($q) => 
                        $q->where('name', 'like', '%' . $request->customer_name . '%')
                    )
                )
                ->when($request->customer_phone, fn($query) => 
                    $query->whereHas('order.phone', fn($q) => 
                        $q->where('number', 'like', '%' . $request->customer_phone . '%')
                    )
                )
                ->when($request->payment_status, fn($query) => 
                    $query->where('payment_status   ', $request->payment_status)
                )
                ->when($request->start_created_at, fn($query) => 
                    $query->whereDate('created_at', '>=', $request->start_created_at)
                )
                ->when($request->end_created_at, fn($query) => 
                    $query->whereDate('created_at', '<=', $request->end_created_at)
                )
                // ->withTrashed()
                ->orderBy('id', 'desc')
                ->paginate(100);

                $counters = Invoice::query()
                    ->whereIn('id', $invoices->pluck('id'))
                    ->select('order_id', DB::raw('COUNT(*) as count'))
                    ->groupBy('order_id')
                    ->get();

                $invoices->each(function($invoice) use ($counters) {
                    $invoice->order_invoices_count = $counters->where('order_id', $invoice->order_id)->first()->count;
                });

            // dd($invoices);
            return InvoiceResource::collection($invoices);
        }


        $departments = Department::query()
            ->where('is_service', true)
            ->get();

        $technicians = User::query()
            ->whereIn('title_id', Title::TECHNICIANS_GROUP)
            ->get();
        return view('pages.invoices.index', [
            'departments' => DepartmentResource::collection($departments),
            'technicians' => UserResource::collection($technicians),
        ]);
    }

    public function exportToExcel(Request $request)
    {
            $invoices = Invoice::query()
            ->with('order.customer')
            ->with('order.phone')
            ->with('order.department')
            ->with('order.technician')
            ->with('invoice_details')
            ->with('invoice_part_details')
            ->with('payments')
            ->when($request->invoice_number, fn($query) => 
                $query->where('id', 'like', '%' . $request->invoice_number . '%')
            )
            ->when($request->order_number, fn($query) => 
                $query->where('order_id', 'like', '%' . $request->order_number . '%')
            )
            ->when($request->department_ids, fn($query) => 
                $query->whereHas('order', fn($q) => 
                    $q->whereIn('department_id', $request->department_ids)
                )
            )
            ->when($request->technician_ids, fn($query) => 
                $query->whereHas('order', fn($q) => 
                    $q->whereIn('technician_id', $request->technician_ids)
                )
            )
            ->when($request->customer_name, fn($query) => 
                $query->whereHas('order.customer', fn($q) => 
                    $q->where('name', 'like', '%' . $request->customer_name . '%')
                )
            )
            ->when($request->customer_phone, fn($query) => 
                $query->whereHas('order.phone', fn($q) => 
                    $q->where('number', 'like', '%' . $request->customer_phone . '%')
                )
            )
            ->when($request->payment_status, fn($query) => 
                $query->where('payment_status   ', $request->payment_status)
            )
            ->when($request->start_created_at, fn($query) => 
                $query->whereDate('created_at', '>=', $request->start_created_at)
            )
            ->when($request->end_created_at, fn($query) => 
                $query->whereDate('created_at', '<=', $request->end_created_at)
            )
            // ->withTrashed()
            ->orderBy('id', 'desc');

            $invoices_count = $invoices->count();
            if($invoices_count > 5000) {
                return response()->json(['error' => __('messages.you_can_only_export_up_to_5000_records')], 400);
            }

            $log_data = [
                'filters' => $request->all(),
                'count' => $invoices_count,
            ];
            // action log
            ActionsLog::logAction('Invoice', 'Export To Excel', 0, 'Invoice exported to excel successfully', $log_data);

            return Excel::download(new InvoicesExport('pages.invoices.excel', 'Invoices', $invoices->get()), 'Invoices.xlsx');
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
