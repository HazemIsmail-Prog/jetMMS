<?php

namespace App\Enums;

enum PaymentStatusEnum:string
{
    case PAID = 'paid';
    case PENDING = 'pending';
    case PARTIALLY_PAID = 'partially_paid';
    case FREE = 'free';

    public function title() : string {
        return match($this){
            PaymentStatusEnum::PAID => __('messages.paid'),
            PaymentStatusEnum::PENDING => __('messages.pending'),
            PaymentStatusEnum::PARTIALLY_PAID => __('messages.partially_paid'),
            PaymentStatusEnum::FREE => __('messages.free'),
        };
    }

    public function color() : string {
        return match($this){
            PaymentStatusEnum::PAID => 'green',
            PaymentStatusEnum::PENDING => 'red',
            PaymentStatusEnum::PARTIALLY_PAID => 'red',
            PaymentStatusEnum::FREE => 'gray',
        };
    }
}