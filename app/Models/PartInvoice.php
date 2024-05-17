<?php

namespace App\Models;

use App\Observers\PartInvoiceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(PartInvoiceObserver::class)]
class PartInvoice extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }


    public function contact(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contact_id');
    }

    public function voucher(): HasOne
    {
        return $this->hasOne(Voucher::class, 'part_invoice_id');
    }

    // Formatters

    public function getFormatedInvoiceAmountAttribute()
    {
        return $this->invoice_amount > 0 ? number_format($this->invoice_amount, 3) : '-';
    }
    public function getFormatedDiscountAmountAttribute()
    {
        return $this->discount_amount > 0 ? number_format($this->discount_amount, 3) : '-';
    }
    public function getFormatedCostAmountAttribute()
    {
        return $this->cost_amount > 0 ? number_format($this->cost_amount, 3) : '-';
    }

    public function getFormatedSalesAmountAttribute()
    {
        return $this->sales_amount > 0 ? number_format($this->sales_amount, 3) : '-';
    }

    public function getFormatedDateAttribute()
    {
        return '<span dir="ltr">' . ($this->date->format('d-m-Y')) . '</span>';
    }
}
