<?php

namespace App\Enums;

enum VoucherTypeEnum:string
{
    case JV = 'jv';
    case BP = 'bp';
    case BR = 'br';

    public function title() : string {
        return match($this){
            VoucherTypeEnum::JV => __('messages.journal_voucher'),
            VoucherTypeEnum::BP => __('messages.bank_payment'),
            VoucherTypeEnum::BR => __('messages.bank_receipt'),
        };
    }
}