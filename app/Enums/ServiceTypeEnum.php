<?php

namespace App\Enums;

enum ServiceTypeEnum:string
{
    case SERVICE = 'service';
    case PART = 'part';

    public function title() : string {
        return match($this){
            ServiceTypeEnum::SERVICE => __('messages.service'),
            ServiceTypeEnum::PART => __('messages.part'),
        };
    }

    public function color() : string {
        return match($this){
            ServiceTypeEnum::SERVICE => 'green',
            ServiceTypeEnum::PART => 'red',
        };
    }
}