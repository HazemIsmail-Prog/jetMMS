<?php

namespace App\Services;

use App\Models\CostCenter;
use App\Models\Invoice;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

class CreateInvoiceVoucher
{
    public static function createVoucher(Invoice $invoice)
    {
        DB::transaction(function () use ($invoice) {
            // Create Voucher for Created Invoice
            $voucher = Voucher::create([
                'created_by' => auth()->id(),
                'invoice_id' => $invoice->id,
                'date' => $invoice->created_at,
                'type' => 'inv',
                'notes' => 'Invoice No. ' . $invoice->id,
            ]);

            // Create Voucher Details For Created Voucher for Created Invoice
            $details = [];
            $details[] =
                [
                    'account_id' => 92, // ذمم موظفين - فواتير مؤجلة  // TODO: make dynamic
                    'narration' => 'Invoice No. ' . $invoice->id,
                    'user_id' => $invoice->order->technician_id,
                    'debit' => $invoice->amount,
                    'credit' => 0,
                ];

            if ($invoice->services_amount_after_discount > 0) {
                $details[] =
                    [
                        'account_id' => $invoice->order->department->income_account_id,
                        'cost_center_id' => CostCenter::SERVICES,
                        'narration' => 'Invoice No. ' . $invoice->id,
                        'user_id' => $invoice->order->technician_id,
                        'debit' => 0,
                        'credit' => $invoice->services_amount_after_discount,
                    ];
            }

            if ($invoice->external_parts_amount > 0) {
                $details[] =
                    [
                        'account_id' => $invoice->order->department->income_account_id,
                        'cost_center_id' => CostCenter::PARTS,
                        'narration' => 'Invoice No. ' . $invoice->id,
                        'user_id' => $invoice->order->technician_id,
                        'debit' => 0,
                        'credit' => $invoice->external_parts_amount,
                    ];
            }

            if ($invoice->internal_parts_amount > 0) {
                $details[] =
                    [
                        'account_id' => 91, // ذمم موظفيين - بضاعة // TODO: make dynamic
                        'cost_center_id' => CostCenter::PARTS,
                        'narration' => 'Invoice No. ' . $invoice->id,
                        'user_id' => $invoice->order->technician_id,
                        'debit' => 0,
                        'credit' => $invoice->internal_parts_amount,
                    ];
            }

            if ($invoice->delivery > 0) {
                $details[] =
                    [
                        'account_id' => $invoice->order->department->income_account_id,
                        'cost_center_id' => CostCenter::DELIVERY,
                        'narration' => 'Invoice No. ' . $invoice->id,
                        'user_id' => $invoice->order->technician_id,
                        'debit' => 0,
                        'credit' => $invoice->delivery,
                    ];
            }

            $voucher->voucherDetails()->createMany($details);

        });
    }
}
