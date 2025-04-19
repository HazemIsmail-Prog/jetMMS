<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\VoucherDetail;

class ProfitLossController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $start_date = Carbon::parse($request->start_date);
            $end_date = Carbon::parse($request->end_date);

            $accounts = Account::query()
                ->select('id', 'name_ar', 'account_id')
                ->whereIn('account_id', [176, 259, 283])
                ->get();


            $voucher_details = VoucherDetail::query()
                ->select('id', 'account_id', 'debit', 'credit')
                ->whereIn('account_id', $accounts->pluck('id'))
                ->withWhereHas('voucher', function ($query) use ($start_date, $end_date) {
                    $query->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                })
                ->get();


                $income_accounts = $accounts->where('account_id', 176);
                $income_voucher_details = $voucher_details->whereIn('account_id', $income_accounts->pluck('id'));

            $income_data = [];
            foreach ($income_accounts as $account) {
                $income_data[$account->id] = [
                    'id' => $account->id,
                    'name' => $account->name_ar,
                    'total' => abs($income_voucher_details->where('account_id', $account->id)->sum('debit') - $income_voucher_details->where('account_id', $account->id)->sum('credit')),
                ];
            }



            $cost_accounts = $accounts->where('account_id', 259);
            $cost_voucher_details = $voucher_details->whereIn('account_id', $cost_accounts->pluck('id'));

            $cost_data = [];
            foreach ($cost_accounts as $account) {
                $cost_data[$account->id] = [
                    'id' => $account->id,
                    'name' => $account->name_ar,
                    'total' => abs($cost_voucher_details->where('account_id', $account->id)->sum('debit') - $cost_voucher_details->where('account_id', $account->id)->sum('credit')),
                ];
            }
            $expense_accounts = $accounts->where('account_id', 283);
            $expense_voucher_details = $voucher_details->whereIn('account_id', $expense_accounts->pluck('id'));

            $expense_data = [];
            foreach ($expense_accounts as $account) {
                $expense_data[$account->id] = [
                    'id' => $account->id,
                    'name' => $account->name_ar,
                    'total' => abs($expense_voucher_details->where('account_id', $account->id)->sum('debit') - $expense_voucher_details->where('account_id', $account->id)->sum('credit')),
                ];
            }


            return response()->json([
                'income_data' => $income_data,
                'cost_data' => $cost_data,
                'expense_data' => $expense_data,
            ]);
        }
        return view('livewire.accounting-reports.profit_loss');
    }
}
