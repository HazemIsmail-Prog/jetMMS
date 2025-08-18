<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Setting;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

class CreateCostVoucher
{
    public static function createVoucher(Invoice $invoice)
    {
        DB::transaction(function () use ($invoice) {
            // Create Voucher for Created Invoice
            $voucher = Voucher::create([
                'created_by' => auth()->id(),
                'invoice_id' => $invoice->id,
                'date' => $invoice->created_at,
                'type' => 'cost',
                'notes' => 'تكاليف بضاعة خارجية للفاتورة رقم ' . $invoice->id,
            ]);

            // Create Voucher Details For Created Voucher for Created Invoice
            $details = [];
            $details[] =
                [
                    'account_id' => $invoice->order->department->cost_account_id,
                    'narration' => 'تكاليف بضاعة خارجية للفاتورة رقم ' . $invoice->id,
                    'user_id' => $invoice->order->technician_id,
                    'debit' => $invoice->external_parts_amount,
                    'credit' => 0,
                ];

            $details[] =
                [
                    'account_id' => $invoice->order->department->cash_account_id, // الخزينة
                    'narration' => 'تكاليف بضاعة خارجية للفاتورة رقم ' . $invoice->id,
                    'user_id' => $invoice->order->technician_id,
                    'debit' => 0,
                    'credit' => $invoice->external_parts_amount,
                ];


            $voucher->voucherDetails()->createMany($details);
        });
    }
}
