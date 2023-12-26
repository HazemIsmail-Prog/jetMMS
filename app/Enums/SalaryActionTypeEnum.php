<?php

namespace App\Enums;

enum SalaryActionTypeEnum:string
{
    case INCREASE = 'increase';
    case DEDUCTION = 'deduction';


    public function title() : string {
        return match($this){
            SalaryActionTypeEnum::INCREASE => __('messages.increase'),
            SalaryActionTypeEnum::DEDUCTION => __('messages.deduction'),
        };
    }

    public function color() : string {
        return match($this){
            SalaryActionTypeEnum::INCREASE => 'text-green-400',
            SalaryActionTypeEnum::DEDUCTION => 'text-red-400',
        };
    }
}