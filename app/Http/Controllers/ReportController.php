<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Title;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Models\VoucherDetail;
use App\Http\Resources\AccountResource;

class ReportController extends Controller
{
    public function invoices()
    {
        return view('pages.reports.invoices');
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

    public function trial_balance(Request $request)
    {

        abort_if(!auth()->user()->hasPermission('trial_balance_report'), 403);

        
        
        if($request->wantsJson()){

            $start_date = $request->start_date;

            $end_date = $request->end_date;

            $opening_voucher_details = VoucherDetail::query()
                ->select(
                    'account_id', 
                    DB::raw('SUM(debit) as opening_debit'),
                    DB::raw('SUM(credit) as opening_credit'),
                    
                    )
                ->whereHas('voucher', function ($query) use ($start_date) {
                    $query->where('date', '<', $start_date);
                })
                ->groupBy('account_id')
                ->get()->toArray();

          
            $transactions_voucher_details = VoucherDetail::query()
                ->select(
                    'account_id', 
                    DB::raw('SUM(debit) as transactions_debit'),
                    DB::raw('SUM(credit) as transactions_credit'),
                    
                    )
                ->whereHas('voucher', function ($query) use ($start_date,$end_date) {
                    $query->where('date', '>=', $start_date);
                    $query->where('date', '<=', $end_date);
                })
                ->groupBy('account_id')
                ->get()->toArray();


         
            $accounts = Account::query()
                ->where('level', 3)
                ->orderBy('account_id')
                ->get()
        ;

            return [
                'accounts' => AccountResource::collection($accounts),
                'opening_voucher_details' => $opening_voucher_details,
                'transactions_voucher_details' => $transactions_voucher_details,
            ];
        }
        return view('pages.reports.trial_balance');
    }
}
