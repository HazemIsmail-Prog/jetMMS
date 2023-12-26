<?php

namespace App\Enums;

enum LeaveTypeEnum:string
{
    case ANNUAL = 'annual';
    case UNPAID = 'unpaid';
    case SICK = 'sick';
    case URGENT = 'urgent';

    public function title() : string {
        return match($this){
            LeaveTypeEnum::ANNUAL => __('messages.annual'),
            LeaveTypeEnum::UNPAID => __('messages.unpaid'),
            LeaveTypeEnum::SICK => __('messages.sick'),
            LeaveTypeEnum::URGENT => __('messages.urgent'),
        };
    }

    public function color() : string {
        return match($this){
            LeaveTypeEnum::ANNUAL => 'green',
            LeaveTypeEnum::UNPAID => 'green',
            LeaveTypeEnum::SICK => 'red',
            LeaveTypeEnum::URGENT => 'red',
        };
    }
}