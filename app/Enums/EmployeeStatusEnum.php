<?php

namespace App\Enums;

enum EmployeeStatusEnum:string
{
    case ACTIVE = 'active';
    case RESIGNED = 'resigned';
    case TERMINATED = 'terminated';
    case TEMP = 'under_test';
    case ON_LEAVE_OF_ABSENCE = 'on_leave_of_absence';

    public function title() : string {
        return match($this){
            EmployeeStatusEnum::ACTIVE => __('messages.active_employee'),
            EmployeeStatusEnum::RESIGNED => __('messages.resigned'),
            EmployeeStatusEnum::TERMINATED => __('messages.terminated'),
            EmployeeStatusEnum::TEMP => __('messages.under_test'),
            EmployeeStatusEnum::ON_LEAVE_OF_ABSENCE => __('messages.on_leave_of_absence'),
        };
    }

    public function color() : string {
        return match($this){
            EmployeeStatusEnum::ACTIVE => 'oklch(79.2% 0.209 151.711)',
            EmployeeStatusEnum::RESIGNED => 'oklch(70.4% 0.191 22.216)',
            EmployeeStatusEnum::TERMINATED => 'oklch(70.4% 0.191 22.216)',
            EmployeeStatusEnum::TEMP => 'oklch(70.7% 0.022 261.325)',
            EmployeeStatusEnum::ON_LEAVE_OF_ABSENCE => 'oklch(78.9% 0.154 211.53)',
        };
    }
}