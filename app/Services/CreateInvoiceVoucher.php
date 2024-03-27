<?php

namespace App\Services;

use App\Models\CostCenter;
use App\Models\Invoice;
use App\Models\Setting;
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
                'notes' => 'الفاتورة رقم ' . $invoice->id,
            ]);

        CreateInvoiceVoucher::createVoucherDetails($invoice, $voucher);
        });
    }

    public static function createVoucherDetails(Invoice $invoice, Voucher $voucher)
    {

        // Create Voucher Details For Created Voucher for Created Invoice
        $details = [];
        $details[] =
            [
                'account_id' => Setting::find(1)->receivables_account_id, // ذمم موظفين - فواتير مؤجلة
                'narration' => 'الفاتورة رقم ' . $invoice->id,
                'user_id' => $invoice->order->technician_id,
                'debit' => $invoice->amount,
                'credit' => 0,
            ];

        if ($invoice->services_amount_after_discount > 0) {
            $details[] =
                [
                    'account_id' => $invoice->order->department->income_account_id, // ايراد القسم
                    'cost_center_id' => CostCenter::SERVICES,
                    'narration' => 'الفاتورة رقم ' . $invoice->id,
                    'user_id' => $invoice->order->technician_id,
                    'debit' => 0,
                    'credit' => $invoice->services_amount_after_discount,
                ];
        }

        if ($invoice->external_parts_amount > 0) {
            $details[] =
                [
                    'account_id' => $invoice->order->department->income_account_id, // ايراد القسم
                    'cost_center_id' => CostCenter::PARTS,
                    'narration' => 'الفاتورة رقم ' . $invoice->id,
                    'user_id' => $invoice->order->technician_id,
                    'debit' => 0,
                    'credit' => $invoice->external_parts_amount,
                ];
        }

        if ($invoice->internal_parts_amount > 0) {
            $details[] =
                [
                    'account_id' => Setting::find(1)->internal_parts_account_id, // ذمم موظفين - بضاعة
                    'cost_center_id' => CostCenter::PARTS,
                    'narration' => 'الفاتورة رقم ' . $invoice->id,
                    'user_id' => $invoice->order->technician_id,
                    'debit' => 0,
                    'credit' => $invoice->internal_parts_amount,
                ];
        }

        if ($invoice->delivery > 0) {
            $details[] =
                [
                    'account_id' => $invoice->order->department->income_account_id, // ايراد القسم
                    'cost_center_id' => CostCenter::DELIVERY,
                    'narration' => 'الفاتورة رقم ' . $invoice->id,
                    'user_id' => $invoice->order->technician_id,
                    'debit' => 0,
                    'credit' => $invoice->delivery,
                ];
        }

        $voucher->voucherDetails()->createMany($details);
    }
}
