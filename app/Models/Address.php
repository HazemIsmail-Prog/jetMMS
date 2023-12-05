<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['area'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function full_address()
    {
        return
            $this->area->name . ' - ' .
            ($this->block ? ' ' . __('messages.short_block') . ' ' . $this->block : '') .
            ($this->street ? ' ' . __('messages.short_street') . ' ' . $this->street : '') .
            ($this->jadda ? ' ' . __('messages.short_jadda') . ' ' . $this->jadda : '') .
            ($this->building ? ' ' . __('messages.short_building') . ' ' . $this->building : '') .
            ($this->floor ? ' ' . __('messages.floor') . ' ' . $this->floor : '') .
            ($this->apartment ? ' ' . __('messages.apartment') . ' ' . $this->apartment : '');
    }

    public function maps_search()
    {
        return 'https://www.google.com/maps/search/' .
            $this->area->name_ar .
            '+قطعة+' . $this->block .
            ($this->street ? '+شارع+' . $this->street : '') .
            ($this->jadda ? '+جادة+' . $this->jadda : '') .
            ($this->building ? '+مبنى+' . $this->building : '');
    }

    public function getFullAddressAttribute()
    {
        return
            $this->area->name . ' - ' .
            ($this->block ? ' ' . __('messages.short_block') . ' ' . $this->block : '') .
            ($this->street ? ' ' . __('messages.short_street') . ' ' . $this->street : '') .
            ($this->jadda ? ' ' . __('messages.short_jadda') . ' ' . $this->jadda : '') .
            ($this->building ? ' ' . __('messages.short_building') . ' ' . $this->building : '') .
            ($this->floor ? ' ' . __('messages.floor') . ' ' . $this->floor : '') .
            ($this->apartment ? ' ' . __('messages.apartment') . ' ' . $this->apartment : '');
    }
}
