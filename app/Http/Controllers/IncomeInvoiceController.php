<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeInvoice;
use App\Models\OtherIncomeCategory;
use Illuminate\Support\Facades\DB;
use App\Services\ActionsLog;
use App\Services\IncomeInvoiceService;
use App\Models\Account;
use App\Http\Resources\AccountResource;
use Illuminate\Support\Facades\Validator;
class IncomeInvoiceController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('income_invoices_menu'), 403);

        if(request()->wantsJson()) {
            $incomeInvoices = IncomeInvoice::query()
                ->orderBy('date', 'desc')
                ->with('payments')
                ->with('attachments')
                ->with('creator')
                ->withCount('attachments')
                ->when(request()->has('other_income_category_ids'), function ($query) {
                    $query->whereIn('other_income_category_id', request()->other_income_category_ids);
                })
                ->paginate(10);
            return response()->json([
                'data' => $incomeInvoices->items(),
                'meta' => [
                    'current_page' => $incomeInvoices->currentPage(),
                    'last_page' => $incomeInvoices->lastPage(),
                    'per_page' => $incomeInvoices->perPage(),
                    'total' => $incomeInvoices->total(),
                ],
            ]);
        }
        // dd(config('constants.bank_accounts_ids'));
        $bankAccounts = AccountResource::collection(Account::whereIn('id', config('constants.bank_accounts_ids'))->get());
        $otherIncomeCategories = OtherIncomeCategory::all();
        return view('pages.income-invoices.index', compact('otherIncomeCategories', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        if(!auth()->user()->hasPermission('income_invoices_create')) {
            return response()->json(['message' => 'You are not authorized to create income invoices'], 403);
        }
        $validatedData = $request->validate([
            'other_income_category_id' => 'required|exists:other_income_categories,id',
            'manual_number' => 'nullable|string|max:255',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'narration' => 'nullable|string',
        ]);

        $validatedData['created_by'] = auth()->id();

        DB::beginTransaction();
        try {
            $incomeInvoice = IncomeInvoice::create($validatedData);
            IncomeInvoiceService::createOrUpdateIncomeInvoiceVoucher($incomeInvoice);
            DB::commit();
            ActionsLog::logAction(model: 'Income Invoice', action: 'Created', id: $incomeInvoice->id, message: 'Income invoice created successfully', new_data: $incomeInvoice->fresh()->toArray());
            return response()->json(['message' => __('messages.income_invoice_created_successfully')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => __('messages.income_invoice_creation_failed')], 500);
        }
    }

    public function update(Request $request, IncomeInvoice $incomeInvoice)
    {
        if(!auth()->user()->hasPermission('income_invoices_edit')) {
            return response()->json(['message' => 'You are not authorized to edit income invoices'], 403);
        }
        $validatedData = $request->validate([
            'other_income_category_id' => 'required|exists:other_income_categories,id',
            'manual_number' => 'nullable|string|max:255',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'narration' => 'nullable|string',
        ]);

        $oldIncomeInvoice = $incomeInvoice->fresh()->toArray();

        DB::beginTransaction();
        try {
            $incomeInvoice->update($validatedData);
            // rollback if total amount of payments is greater than the invoice amount
            if($incomeInvoice->payments()->sum('amount') > $incomeInvoice->amount) {
                // add amount to error bag
                $validator = Validator::make([], []);
                $validator->errors()->add('amount', __('messages.total_amount_of_payments_cannot_be_greater_than_the_invoice_amount'));
                return response()->json(['errors' => $validator->errors()], 400);
            }
            IncomeInvoiceService::createOrUpdateIncomeInvoiceVoucher($incomeInvoice);
            DB::commit();
            ActionsLog::logAction(model: 'Income Invoice', action: 'Updated', id: $incomeInvoice->id, message: 'Income invoice updated successfully', new_data: $incomeInvoice->fresh()->toArray(), old_data: $oldIncomeInvoice);
            return response()->json(['message' => __('messages.income_invoice_updated_successfully')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => __('messages.income_invoice_update_failed'.$e->getMessage())], 500);
        }

    }

    public function destroy(IncomeInvoice $incomeInvoice)
    {
        if(!auth()->user()->hasPermission('income_invoices_delete')) {
            return response()->json(['message' => 'You are not authorized to delete income invoices'], 403);
        }
        $oldIncomeInvoice = $incomeInvoice->fresh()->toArray();
        DB::beginTransaction();
        try {
            IncomeInvoiceService::deleteIncomeInvoiceVoucher($incomeInvoice);
            $incomeInvoice->delete();
            DB::commit();
            ActionsLog::logAction(model: 'Income Invoice', action: 'Deleted', id: $incomeInvoice->id, message: 'Income invoice deleted successfully', new_data: [], old_data: $oldIncomeInvoice);
            return response()->json(['message' => __('messages.income_invoice_deleted_successfully')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => __('messages.income_invoice_deletion_failed')], 500);
        }
    }

    public function getIncomeInvoicePayments(IncomeInvoice $incomeInvoice)
    {
        if(!auth()->user()->hasPermission('income_invoices_payments_menu')) {
            return response()->json(['message' => 'You are not authorized to view income invoice payments'], 403);
        }
        $payments = $incomeInvoice->payments;
        return response()->json([
            'data' => $payments,
        ]);
    }
}
