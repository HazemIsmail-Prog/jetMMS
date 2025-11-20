<?php

namespace App\Services;

use App\Models\IncomeInvoice;
use App\Models\Voucher;
use App\Models\OtherIncomeCategory;
use App\Enums\VoucherTypeEnum;

class IncomeInvoiceService
{
    public static function createOrUpdateIncomeInvoiceVoucher(IncomeInvoice $incomeInvoice)
    {
        $IncomeInvoiceCategory = OtherIncomeCategory::find($incomeInvoice->other_income_category_id);
        if(!$IncomeInvoiceCategory) {
            throw new \Exception('Income invoice category not found');
        }
        $voucher = Voucher::updateOrCreate([
            'income_invoice_id' => $incomeInvoice->id,
        ], [
            'manual_id' => $incomeInvoice->manual_number,
            'created_by' => $incomeInvoice->created_by,
            'income_invoice_id' => $incomeInvoice->id,
            'date' => $incomeInvoice->date,
            'type' => VoucherTypeEnum::INCOME_INVOICE,
            'notes' => $incomeInvoice->narration,
        ]);
        $voucher->voucherDetails()->forceDelete();
        $details = [];
        $details[] = [
            'account_id' => $IncomeInvoiceCategory->expense_account_id,
            'debit' => $incomeInvoice->amount,
            'credit' => 0,
            'narration' => $incomeInvoice->narration,
        ];
        $details[] = [
            'account_id' => $IncomeInvoiceCategory->income_account_id,
            'credit' => $incomeInvoice->amount,
            'debit' => 0,
            'narration' => $incomeInvoice->narration,
        ];
        $voucher->voucherDetails()->createMany($details);
    }

    public static function deleteIncomeInvoiceVoucher(IncomeInvoice $incomeInvoice)
    {
        foreach ($incomeInvoice->vouchers()->withTrashed()->get() as $voucher) {
            foreach($voucher->voucherDetails()->withTrashed()->get() as $detail){
                $detail->forceDelete();
            }
            $voucher->forceDelete();
        }
    }
} 