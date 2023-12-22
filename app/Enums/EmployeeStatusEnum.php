<?php

namespace App\Enums;

enum EmployeeStatusEnum:string
{
    case ACTIVE = 'active';
    case RESIGNED = 'resigned';
    case TERMINATED = 'terminated';
    case TEMP = 'under_test';

    public function title() : string {
        return match($this){
            EmployeeStatusEnum::ACTIVE => __('messages.active_employee'),
            EmployeeStatusEnum::RESIGNED => __('messages.resigned'),
            EmployeeStatusEnum::TERMINATED => __('messages.terminated'),
            EmployeeStatusEnum::TEMP => __('messages.under_test'),
        };
    }

    public function color() : string {
        return match($this){
            EmployeeStatusEnum::ACTIVE => 'green',
            EmployeeStatusEnum::RESIGNED => 'red',
            EmployeeStatusEnum::TERMINATED => 'red',
            EmployeeStatusEnum::TEMP => 'gray',
        };
    }
}