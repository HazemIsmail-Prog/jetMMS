<?php

namespace App\Enums;

enum CancelReasonEnum:string
{
    case DELAYED_ARRIVAL = 'delayed_arrival';
    case UNSUITABLE_PRICES = 'unsuitable_prices';
    case SELF_RESOLVED = 'self_resolved';
    case NO_RESPONSE = 'no_response';
    case OTHER = 'other';

    public function title() : string {
        return match($this){
            CancelReasonEnum::DELAYED_ARRIVAL => __('messages.delayed_arrival'),
            CancelReasonEnum::UNSUITABLE_PRICES => __('messages.unsuitable_prices'),
            CancelReasonEnum::SELF_RESOLVED => __('messages.issue_self_resolved'),
            CancelReasonEnum::NO_RESPONSE => __('messages.no_response_communication'),
            CancelReasonEnum::OTHER => __('messages.other_reason'),
        };
    }
}