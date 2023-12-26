<?php

namespace App\Enums;

enum LeaveStatusEnum:string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function title() : string {
        return match($this){
            LeaveStatusEnum::PENDING => __('messages.pending_leave'),
            LeaveStatusEnum::APPROVED => __('messages.approved'),
            LeaveStatusEnum::REJECTED => __('messages.rejected'),
        };
    }

    public function color() : string {
        return match($this){
            LeaveStatusEnum::PENDING => 'green',
            LeaveStatusEnum::APPROVED => 'red',
            LeaveStatusEnum::REJECTED => 'red',
        };
    }
}