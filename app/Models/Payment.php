<?php

namespace App\Models;

use App\Observers\PaymentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


#[ObservedBy(PaymentObserver::class)]
class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Formatters

    public function getFormatedCreatedAtAttribute()
    {
        return '<span dir="ltr">' . ($this->created_at->format('d-m-Y | H:i')) . '</span>';
    }

    public function getFormatedAmountAttribute()
    {
        return $this->amount > 0 ? number_format($this->amount, 3) : '-';
    }
}
