<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeInvoice;
use Illuminate\Support\Facades\DB;
use App\Services\ActionsLog;

class IncomeReconciliationController extends Controller
{
    public function store(IncomeInvoice $incomeInvoice, Request $request)
    {
        
        // check if authenticated user has permission to create reconciliation
        if (!auth()->user()->hasPermission('income_reconciliations_create')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $incomeInvoiceRemainingBalance = $incomeInvoice->amount - $incomeInvoice->payments->sum('amount') - $incomeInvoice->incomeReconciliations->sum('amount');
        
        // check if reconciliation amount is greater than invoice remaining balance
        if ($request->amount > round($incomeInvoiceRemainingBalance , 3)) {
            return response()->json(['error' => 'Income reconciliation amount is greater than income invoice remaining balance'], 400);
        }
        
        $validatedReconciliationData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string',
            'related_user_id' => 'nullable|exists:users,id|required_if:type,deduction',
        ]);
        
        $validatedReconciliationData['created_by'] = auth()->user()->id;
        $validatedReconciliationData['updated_by'] = auth()->user()->id;
        $validatedReconciliationData['reconciliation_date'] = today();
        // dd($validatedReconciliationData);

        DB::beginTransaction();

        try {
            $incomeReconciliation = $incomeInvoice->incomeReconciliations()->create($validatedReconciliationData);
            $voucher = $incomeReconciliation->vouchers()->create([
                'date' => now(),
                'type' => 'income_reconciliation',
                'notes' => 'تسوية فاتورة ايراد رقم ' . $incomeInvoice->id,
                'created_by' => auth()->user()->id,
            ]);
            $voucher->voucherDetails()->createMany($this->getVoucherDetails($validatedReconciliationData, $incomeInvoice));

            DB::commit();
            ActionsLog::logAction('IncomeInvoice', 'Income Reconciliation Created', $incomeInvoice->id, 'Income reconciliation created successfully', $incomeReconciliation->fresh()->toArray());
            return $incomeInvoice->load('payments','incomeReconciliations','vouchers');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    private function getVoucherDetails($validatedReconciliationData, $incomeInvoice)
    {
        $narration = 'تسوية فاتورة ايراد رقم ' . $incomeInvoice->id;
        
        if($validatedReconciliationData['type'] == 'deduction') {
            $creditAccountId = $incomeInvoice->otherIncomeCategory->expense_account_id;
            $debitAccountId = 89;  // ذمم موظفين - سلف = 89
            return [
                    [
                        'account_id' => $debitAccountId,
                        'debit' => $validatedReconciliationData['amount'],
                        'credit' => 0,
                        'narration' => $narration,
                        'user_id' => $validatedReconciliationData['related_user_id'] ?? null,
                    ],
                    [
                        'account_id' => $creditAccountId,
                        'credit' => $validatedReconciliationData['amount'],
                        'debit' => 0,
                        'narration' => $narration,
                        'user_id' => $validatedReconciliationData['related_user_id'] ?? null,
                    ]
                ];
        }

        if($validatedReconciliationData['type'] == 'sales_refund') {
            $debitAccountId = $incomeInvoice->otherIncomeCategory->refund_account_id;
            $creditAccountId = $incomeInvoice->otherIncomeCategory->expense_account_id;
            return [
                [
                    'account_id' => $debitAccountId,
                    'debit' => $validatedReconciliationData['amount'],
                    'credit' => 0,
                    'narration' => $narration,
                ],
                [
                    'account_id' => $creditAccountId,
                    'credit' => $validatedReconciliationData['amount'],
                    'debit' => 0,
                    'narration' => $narration,
                ]
            ];
        }

        if($validatedReconciliationData['type'] == 'costs') {
            $debitAccountId = $incomeInvoice->otherIncomeCategory->cost_account_id;
            $creditAccountId = $incomeInvoice->otherIncomeCategory->expense_account_id;
            return [
                [
                    'account_id' => $debitAccountId,
                    'debit' => $validatedReconciliationData['amount'],
                    'credit' => 0,
                    'narration' => $narration,
                ],
                [
                    'account_id' => $creditAccountId,
                    'credit' => $validatedReconciliationData['amount'],
                    'debit' => 0,
                    'narration' => $narration,
                ]
            ];
        }



    }
}
