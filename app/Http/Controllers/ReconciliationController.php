<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Http\Resources\InvoiceResource;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Services\ActionsLog;

class ReconciliationController extends Controller
{

    public function store(Invoice $invoice, Request $request)
    {

        // check if authenticated user has permission to create reconciliation
        if (!auth()->user()->hasPermission('reconciliations_create')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // get invoice remaining balance from invoiceResource
        $invoiceResource = new InvoiceResource($invoice->load('invoice_details','invoice_part_details','payments','reconciliations'));
        $invoiceArray = $invoiceResource->toArray(request());
        $invoiceRemainingBalance = $invoiceArray['remaining_balance'];

        // check if reconciliation amount is greater than invoice remaining balance
        if ($request->amount > $invoiceRemainingBalance) {
            return response()->json(['error' => 'Reconciliation amount is greater than invoice remaining balance'], 400);
        }

        $validatedReconciliationData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string',
            'related_user_id' => 'nullable|exists:users,id|required_if:type,deduction',
        ]);

        $validatedReconciliationData['created_by'] = auth()->user()->id;
        $validatedReconciliationData['updated_by'] = auth()->user()->id;
        $validatedReconciliationData['reconciliation_date'] = today();

        DB::beginTransaction();

        try {
            $reconciliation = $invoice->reconciliations()->create($validatedReconciliationData);
            $voucher = $invoice->vouchers()->create([
                'date' => now(),
                'type' => 'reconciliation',
                'notes' => 'تسوية الفاتورة رقم ' . $invoice->id,
                'reconciliation_id' => $reconciliation->id,
                'created_by' => auth()->user()->id,
            ]);
            $voucher->voucherDetails()->createMany($this->getVoucherDetails($validatedReconciliationData, $invoice));

            DB::commit();
            ActionsLog::logAction('Invoice', 'Reconciliation Created', $invoice->id, 'Reconciliation created successfully', $reconciliation->fresh()->toArray());
            return new InvoiceResource($invoice);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    private function getVoucherDetails($validatedReconciliationData, $invoice)
    {

        // ذمم موظفين - سلف = 89
        // ذمم موظفين - فواتير مؤجلة = 92
        // مردودات العمليات - فنيين الشركة = 249
        // مردودات العمليات - فنيين بالنسبة = 250
        // مردودات مقاولين = 251

        $forAllCreditAccountId = 92;
        $narration = 'تسوية الفاتورة رقم ' . $invoice->id;

        if($validatedReconciliationData['type'] == 'deduction') {
            $debitAccountId = 89;
            return [
                    [
                        'account_id' => $debitAccountId,
                        'debit' => $validatedReconciliationData['amount'],
                        'credit' => 0,
                        'narration' => $narration,
                        'user_id' => $validatedReconciliationData['related_user_id'] ?? null,
                    ],
                    [
                        'account_id' => $forAllCreditAccountId,
                        'credit' => $validatedReconciliationData['amount'],
                        'debit' => 0,
                        'narration' => $narration,
                        'user_id' => $validatedReconciliationData['related_user_id'] ?? null,
                    ]
                ];
        }

        if($validatedReconciliationData['type'] == 'technician_refund') {
            $orderTechnician = User::find($invoice->order->technician_id);
            if($orderTechnician->title_id == 11) {
                // فنيين الشركة
                $debitAccountId = 249;
            }

            if($orderTechnician->title_id == 26) {
                // فنيين بالنسبة
                $debitAccountId = 250;
            }
            return [
                [
                    'account_id' => $debitAccountId,
                    'debit' => $validatedReconciliationData['amount'],
                    'credit' => 0,
                    'narration' => $narration,
                    'user_id' => $orderTechnician->id,
                ],
                [
                    'account_id' => $forAllCreditAccountId,
                    'credit' => $validatedReconciliationData['amount'],
                    'debit' => 0,
                    'narration' => $narration,
                    'user_id' => $orderTechnician->id,
                ]
            ];
        }

        if($validatedReconciliationData['type'] == 'contractor_refund') {
            $debitAccountId = 251;
            $orderTechnician = User::find($invoice->order->technician_id);
            return [
                [
                    'account_id' => $debitAccountId,
                    'debit' => $validatedReconciliationData['amount'],
                    'credit' => 0,
                    'narration' => $narration,
                    'user_id' => $orderTechnician->id,
                ],
                [
                    'account_id' => $forAllCreditAccountId,
                    'credit' => $validatedReconciliationData['amount'],
                    'debit' => 0,
                    'narration' => $narration,
                    'user_id' => $orderTechnician->id,
                ]
            ];
        }

        if($validatedReconciliationData['type'] == 'costs') {
            $debitAccountId = $invoice->order->department->cost_account_id;
            $orderTechnician = User::find($invoice->order->technician_id);
            return [
                [
                    'account_id' => $debitAccountId,
                    'debit' => $validatedReconciliationData['amount'],
                    'credit' => 0,
                    'narration' => $narration,
                    'user_id' => $orderTechnician->id,
                ],
                [
                    'account_id' => $forAllCreditAccountId,
                    'credit' => $validatedReconciliationData['amount'],
                    'debit' => 0,
                    'narration' => $narration,
                    'user_id' => $orderTechnician->id,
                ]
            ];
        }



    }
}
