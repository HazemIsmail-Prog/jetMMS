<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    public function phones()
    {
        return $this->hasMany(Phone::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function invoices()
    {
        return $this->hasManyThrough(Invoice::class,Order::class);
    }

    public function getBalanceAttribute()
    {
        return $this->invoices->sum('remaining_amount');
    }
}
