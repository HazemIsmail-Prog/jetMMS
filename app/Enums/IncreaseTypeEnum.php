<?php

namespace App\Enums;

enum IncreaseTypeEnum:string
{
    case BASIC = 'basic';
    case ALLOWANCE = 'allowance';


    public function title() : string {
        return match($this){
            IncreaseTypeEnum::BASIC => __('messages.basic'),
            IncreaseTypeEnum::ALLOWANCE => __('messages.allowance'),
        };
    }

    public function color() : string {
        return match($this){
            IncreaseTypeEnum::BASIC => 'green',
            IncreaseTypeEnum::ALLOWANCE => 'green',
        };
    }
}