<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    // protected $appends = ['payment_status'];

    protected $casts = [
        'payment_status' => PaymentStatusEnum::class
    ];

    public function invoice_details()
    {
        return $this->hasMany(InvoiceDetails::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    // this function to prevent eager loading for get attributes
    public function newQuery($excludeDeleted = true)
    {
        return parent::newQuery($excludeDeleted)->with('invoice_details');
    }

    public function getAmountAttribute()
    {
        return $this->invoice_details->sum(function ($detail) {
            return $detail->quantity * $detail->price;
        });
    }

    // public function getPaymentStatusAttribute()
    // {
    //     if ($this->payments()->count() == 0) {
    //         return $this->amount > 0 ? 'pending' : 'free' ;
    //     } else {
    //         return $this->remaining_amount == 0 ? 'paid' : 'partially_paid' ;
    //     }
    // }

    public function getServicesAmountAttribute()
    {
        return $this->invoice_details->where('service.type','service')->sum('total');;
    }
    public function getPartsAmountAttribute()
    {
        return $this->invoice_details->where('service.type','part')->sum('total');;
    }

    public function getCashAmountAttribute()
    {
        return $this-> payments->where('method', 'cash')->sum('amount');
    }

    public function getKnetAmountAttribute()
    {
        return $this-> payments->where('method', 'knet')->sum('amount');
    }

    public function getTotalPaidAmountAttribute()
    {
        return $this-> payments()->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->amount - $this->total_paid_amount;
    }
}
