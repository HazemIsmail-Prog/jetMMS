<?php

namespace App\Services;

use App\Models\CostCenter;
use App\Models\PartInvoice;
use App\Models\Setting;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

class CreatePartInvoiceVoucher
{
    public static function createVoucher(PartInvoice $part_invoice)
    {
        DB::transaction(function () use ($part_invoice) {
            // Create Voucher for Created PartInvoice
            $voucher = Voucher::create([
                'created_by' => auth()->id(),
                'part_invoice_id' => $part_invoice->id,
                'manual_id' => $part_invoice->manual_id,
                'date' => $part_invoice->date,
                'type' => 'part_invoice',
                'notes' => 'فاتورة بضاعة عهدة رقم ' . $part_invoice->id,
            ]);

            CreatePartInvoiceVoucher::createVoucherDetails($part_invoice,$voucher);

        });
    }

    public static function editVoucher(PartInvoice $part_invoice, Voucher $voucher)
    {
        DB::transaction(function () use ($part_invoice,$voucher) {
            // Create Voucher for Created PartInvoice
            $voucher->update([
                'manual_id' => $part_invoice->manual_id,
                'date' => $part_invoice->date,
            ]);

            $voucher->voucherDetails()->forceDelete();
            CreatePartInvoiceVoucher::createVoucherDetails($part_invoice,$voucher);

        });
    }

    public static function createVoucherDetails(PartInvoice $part_invoice, Voucher $voucher)
    {
        // Create Voucher Details For Created Voucher for Created PartInvoice
        $details = [];
        $details[] =
            [
                'account_id' => $part_invoice->contact->department->cost_account_id,
                'narration' => 'فاتورة بضاعة عهدة رقم ' . $part_invoice->id,
                'cost_center_id' => CostCenter::PARTS,
                'user_id' => $part_invoice->contact_id,
                'debit' => $part_invoice->cost_amount,
                'credit' => 0,
            ];

        $details[] =
            [
                'account_id' => $part_invoice->supplier->account_id,
                'narration' => 'فاتورة بضاعة عهدة رقم ' . $part_invoice->id,
                'cost_center_id' => CostCenter::PARTS,
                'user_id' => $part_invoice->contact_id,
                'debit' => 0,
                'credit' => $part_invoice->cost_amount,
            ];

        $details[] =
            [
                'account_id' => $part_invoice->contact->department->internal_parts_account_id, // ذمم موظفين - بضاعة
                'narration' => 'فاتورة بضاعة عهدة رقم ' . $part_invoice->id,
                'cost_center_id' => CostCenter::PARTS,
                'user_id' => $part_invoice->contact_id,
                'debit' => $part_invoice->sales_amount,
                'credit' => 0,
            ];

        $details[] =
            [
                'account_id' => $part_invoice->contact->department->income_account_id,
                'narration' => 'فاتورة بضاعة عهدة رقم ' . $part_invoice->id,
                'cost_center_id' => CostCenter::PARTS,
                'user_id' => $part_invoice->contact_id,
                'debit' => 0,
                'credit' => $part_invoice->sales_amount,
            ];


        $voucher->voucherDetails()->createMany($details);
    }
}
