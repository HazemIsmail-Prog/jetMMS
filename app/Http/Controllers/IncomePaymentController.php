<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeInvoice;
use App\Models\IncomePayment;
use App\Services\IncomePaymentService;
use Illuminate\Support\Facades\DB;
use App\Services\ActionsLog;
use Illuminate\Support\Facades\Validator;
class IncomePaymentController extends Controller
{
    public function index(IncomeInvoice $incomeInvoice){
        if(request()->wantsJson()) {
            $incomePayments = $incomeInvoice->payments()
                ->orderBy('date', 'desc')
                ->with('creator')
                ->paginate(10);
            return response()->json([
                'data' => $incomePayments->items(),
                'meta' => [
                    'current_page' => $incomePayments->currentPage(),
                    'last_page' => $incomePayments->lastPage(),
                    'per_page' => $incomePayments->perPage(),
                    'total' => $incomePayments->total(),
                ],
            ]);
        }
    }

    public function store(Request $request, IncomeInvoice $incomeInvoice){
        $validatedData = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'method' => 'required|string',
            'bank_account_id' => 'nullable|required_if:method,bank_deposit|exists:accounts,id',
            'narration' => 'nullable|string',
        ]);

        $validatedData['created_by'] = auth()->id();
        // check if amount is greater than the max available amount
        $maxAvailableAmount = $incomeInvoice->amount - $incomeInvoice->payments()->sum('amount');
        if($validatedData['amount'] > $maxAvailableAmount) {
            $validator = Validator::make([], []);
            $validator->errors()->add('amount', __('messages.amount_cannot_be_greater_than_the_max_available_amount') . ' ' . $maxAvailableAmount);
            return response()->json(['errors' => $validator->errors()], 400);
        }
        // check if amount is negative or zero
        if($validatedData['amount'] <= 0) {
            $validator = Validator::make([], []);
            $validator->errors()->add('amount', __('messages.amount_cannot_be_negative_or_zero'));
            return response()->json(['errors' => $validator->errors()], 400);
        }
        DB::beginTransaction();
        try {
            $incomePayment = $incomeInvoice->payments()->create($validatedData);
            IncomePaymentService::createOrUpdateIncomePaymentVoucher($incomePayment);
            DB::commit();
            ActionsLog::logAction(model: 'Income Payment', action: 'Created', id: $incomePayment->id, message: 'Income payment created successfully', new_data: $incomePayment->fresh()->toArray());
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['data' => $incomePayment], 200);
    }

    public function update(Request $request, IncomeInvoice $incomeInvoice, IncomePayment $incomePayment){
        $validatedData = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'method' => 'required|string',
            'bank_account_id' => 'nullable|required_if:method,bank_deposit|exists:accounts,id',
            'narration' => 'nullable|string',
        ]);

        // check if amount is negative or zero
        if($validatedData['amount'] <= 0) {
            $validator = Validator::make([], []);
            $validator->errors()->add('amount', __('messages.amount_cannot_be_negative_or_zero'));
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $oldIncomePayment = $incomePayment->fresh()->toArray();
        DB::beginTransaction();
        try {
            $incomePayment->update($validatedData);
            // rollback if total amount of payments is greater than the invoice amount
            if($incomeInvoice->payments()->sum('amount') > $incomeInvoice->amount) {
                // add amount to error bag
                $validator = Validator::make([], []);
                $validator->errors()->add('amount', __('messages.total_amount_of_payments_cannot_be_greater_than_the_invoice_amount'));
                return response()->json(['errors' => $validator->errors()], 400);
            }
            IncomePaymentService::createOrUpdateIncomePaymentVoucher($incomePayment);
            DB::commit();
            ActionsLog::logAction(model: 'Income Payment', action: 'Updated', id: $incomePayment->id, message: 'Income payment updated successfully', new_data: $incomePayment->fresh()->toArray(), old_data: $oldIncomePayment);
            return response()->json(['data' => $incomePayment], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(IncomeInvoice $incomeInvoice, IncomePayment $incomePayment){
        $oldIncomePayment = $incomePayment->fresh()->toArray();
        DB::beginTransaction();
        try {
            IncomePaymentService::deleteIncomePaymentVoucher($incomePayment);
            $incomePayment->delete();
            DB::commit();
            ActionsLog::logAction(model: 'Income Payment', action: 'Deleted', id: $incomePayment->id, message: 'Income payment deleted successfully', new_data: [], old_data: $oldIncomePayment);
            return response()->json(['data' => $incomePayment], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
