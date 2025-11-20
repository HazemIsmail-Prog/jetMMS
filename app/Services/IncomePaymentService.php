<?php

namespace App\Services;

use App\Models\IncomeInvoice;
use App\Models\IncomePayment;
use App\Models\Voucher;
use App\Models\OtherIncomeCategory;
use App\Enums\VoucherTypeEnum;

class IncomePaymentService
{
    public static function createOrUpdateIncomePaymentVoucher(IncomePayment $incomePayment)
    {
        $incomeInvoiceCategory = OtherIncomeCategory::find($incomePayment->incomeInvoice->other_income_category_id);
        if(!$incomeInvoiceCategory) {
            throw new \Exception('Income invoice category not found');
        }
        $debitAccount = null;
        switch($incomePayment->method){
            case 'cash':
                $debitAccount = $incomeInvoiceCategory->cash_account_id;
                break;
            case 'knet':
                $debitAccount = $incomeInvoiceCategory->knet_account_id;
                break;
            case 'bank_deposit':
                $debitAccount = $incomePayment->bank_account_id;
                break;
        }
        if(!$debitAccount) {
            throw new \Exception('Debit account not found');
        }
        $creditAccount = $incomeInvoiceCategory->expense_account_id;
        if(!$creditAccount) {
            throw new \Exception('Credit account not found');
        }
        $voucher = Voucher::updateOrCreate([
            'income_payment_id' => $incomePayment->id,
        ], [
            'created_by' => $incomePayment->created_by,
            'income_payment_id' => $incomePayment->id,
            'date' => $incomePayment->date,
            'type' => VoucherTypeEnum::INCOME_PAYMENT,
            'notes' => $incomePayment->narration,
        ]);
        $voucher->voucherDetails()->forceDelete();
        $details = [];
        $details[] = [
            'account_id' => $debitAccount,
            'debit' => $incomePayment->amount,
            'credit' => 0,
            'narration' => $incomePayment->narration,
        ];
        $details[] = [
            'account_id' => $creditAccount,
            'credit' => $incomePayment->amount,
            'debit' => 0,
            'narration' => $incomePayment->narration,
        ];
        $voucher->voucherDetails()->createMany($details);
    }

    public static function deleteIncomePaymentVoucher(IncomePayment $incomePayment)
    {
        foreach ($incomePayment->vouchers()->withTrashed()->get() as $voucher) {
            foreach($voucher->voucherDetails()->withTrashed()->get() as $detail){
                $detail->forceDelete();
            }
            $voucher->forceDelete();
        }
    }
} 