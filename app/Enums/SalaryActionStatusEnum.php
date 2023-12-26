<?php

namespace App\Enums;

enum SalaryActionStatusEnum:string
{
    case PENDING = 'pending';
    case DONE = 'done';


    public function title() : string {
        return match($this){
            SalaryActionStatusEnum::PENDING => __('messages.salary_action_pending'),
            SalaryActionStatusEnum::DONE => __('messages.salary_action_done'),
        };
    }

    public function color() : string {
        return match($this){
            SalaryActionStatusEnum::PENDING => 'text-orange-400',
            SalaryActionStatusEnum::DONE => 'text-green-400',
        };
    }
}