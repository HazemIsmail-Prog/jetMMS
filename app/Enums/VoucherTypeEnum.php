<?php

namespace App\Enums;

enum VoucherTypeEnum:string
{
    case JV = 'jv';
    case INVOICE = 'inv';
    case INVOICE_CASH_PAYMENT = 'invoice_cash_payment';
    case INVOICE_KNET_PAYMENT = 'invoice_knet_payment';
    case PART_INVOICE = 'part_invoice';
    case COST = 'cost';
    case RECONCILIATION = 'reconciliation';

    public function title() : string {
        return match($this){
            VoucherTypeEnum::JV => __('messages.journal_voucher'),
            VoucherTypeEnum::INVOICE => __('messages.invoice_voucher'),
            VoucherTypeEnum::INVOICE_CASH_PAYMENT => __('messages.invoice_cash_payment_voucher'),
            VoucherTypeEnum::INVOICE_KNET_PAYMENT => __('messages.invoice_knet_payment_voucher'),
            VoucherTypeEnum::PART_INVOICE => __('messages.part_invoice_voucher'),
            VoucherTypeEnum::COST => __('messages.cost_voucher'),
            VoucherTypeEnum::RECONCILIATION => __('messages.reconciliation_voucher'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            VoucherTypeEnum::JV => 'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-300',
            VoucherTypeEnum::INVOICE => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            VoucherTypeEnum::INVOICE_CASH_PAYMENT => 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300',
            VoucherTypeEnum::INVOICE_KNET_PAYMENT => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-300',
            VoucherTypeEnum::PART_INVOICE => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-700 dark:text-indigo-300',
            VoucherTypeEnum::COST => 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-300',
            VoucherTypeEnum::RECONCILIATION => 'bg-purple-100 text-purple-800 dark:bg-purple-700 dark:text-purple-300',
        };
    }
}