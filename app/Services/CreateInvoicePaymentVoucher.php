<?php

namespace App\Services;

use App\Models\Payment;
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
                    'account_id' => 53, // الخزينة 1
                    'narration' => 'دفع نقدي للفاتورة رقم ' . $payment->invoice_id,
                    'user_id' => $payment->invoice->order->technician_id,
                    'debit' => $payment->amount,
                    'credit' => 0,
                ];

            $details[] =
                [
                    'account_id' => 92,  // ذمم موظفيين - فواتير مؤجلة
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
                    'account_id' => 57, // البنك  // TODO: make dynamic
                    'narration' => 'دفع K-Net للفاتورة رقم ' . $payment->invoice_id,
                    'user_id' => $payment->invoice->order->technician_id,
                    'debit' => $payment->amount - 0.07, // TODO: make dynamic
                    'credit' => 0,
                ];

            $details[] =
                [
                    'account_id' => 366, // مصروف عمولات روابط بنكية  // TODO: make dynamic
                    'narration' => 'دفع K-Net للفاتورة رقم ' . $payment->invoice_id,
                    'user_id' => $payment->invoice->order->technician_id,
                    'debit' => 0.07, // TODO: make dynamic
                    'credit' => 0,
                ];

            $details[] =
                [
                    'account_id' => 92,  // ذمم موظفيين - فواتير مؤجلة  // TODO: make dynamic
                    'narration' => 'دفع K-Net للفاتورة رقم ' . $payment->invoice_id,
                    'user_id' => $payment->invoice->order->technician_id,
                    'debit' => 0,
                    'credit' => $payment->amount,
                ];



            $voucher->voucherDetails()->createMany($details);
        });
    }
}
