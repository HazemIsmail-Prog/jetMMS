<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Setting;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

class CreateInvoicePaymentVoucher
{
    public static function createCashPaymentVoucher(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            // Create Voucher for Created Payment
            $voucher = Voucher::create([
                'created_by' => auth()->id(),
                'invoice_id' => $payment->invoice_id,
                'payment_id' => $payment->id,
                'date' => $payment->created_at,
                'type' => 'invoice_cash_payment',
                'notes' => 'دفع نقدي للفاتورة رقم ' . $payment->invoice_id,
            ]);

            // Create Voucher Details For Created Voucher for Created Payment

            $details = [];
            $details[] =
                [
                    'account_id' => $payment->invoice->order->department->cash_account_id, // الخزينة 1
                    'narration' => 'دفع نقدي للفاتورة رقم ' . $payment->invoice_id,
                    'user_id' => $payment->invoice->order->technician_id,
                    'debit' => $payment->amount,
                    'credit' => 0,
                ];

            $details[] =
                [
                    'account_id' => $payment->invoice->order->department->receivables_account_id,  // ذمم موظفيين - فواتير مؤجلة
                    'narration' => 'دفع نقدي للفاتورة رقم ' . $payment->invoice_id,
                    'user_id' => $payment->invoice->order->technician_id,
                    'debit' => 0,
                    'credit' => $payment->amount,
                ];



            $voucher->voucherDetails()->createMany($details);
        });
    }

    public static function createKnetPaymentVoucher(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            // Create Voucher for Created Payment
            $voucher = Voucher::create([
                'created_by' => auth()->id(),
                'invoice_id' => $payment->invoice_id,
                'payment_id' => $payment->id,
                'date' => $payment->created_at,
                'type' => 'invoice_knet_payment',
                'notes' => 'دفع K-Net للفاتورة رقم ' . $payment->invoice_id,
            ]);

            // Create Voucher Details For Created Voucher for Created Payment

            $details = [];
            $details[] =
                [
                    'account_id' => $payment->invoice->order->department->bank_account_id, // البنك
                    'narration' => 'دفع K-Net للفاتورة رقم ' . $payment->invoice_id,
                    'user_id' => $payment->invoice->order->technician_id,
                    'debit' => $payment->amount - Setting::find(1)->knet_tax, // عمولة البنك
                    'credit' => 0,
                ];

            $details[] =
                [
                    'account_id' => $payment->invoice->order->department->bank_charges_account_id, // مصروف عمولات روابط بنكية
                    'narration' => 'دفع K-Net للفاتورة رقم ' . $payment->invoice_id,
                    'user_id' => $payment->invoice->order->technician_id,
                    'debit' => Setting::find(1)->knet_tax, // عمولة البنك
                    'credit' => 0,
                ];

            $details[] =
                [
                    'account_id' => $payment->invoice->order->department->receivables_account_id,  // ذمم موظفيين - فواتير مؤجلة
                    'narration' => 'دفع K-Net للفاتورة رقم ' . $payment->invoice_id,
                    'user_id' => $payment->invoice->order->technician_id,
                    'debit' => 0,
                    'credit' => $payment->amount,
                ];



            $voucher->voucherDetails()->createMany($details);
        });
    }
}
