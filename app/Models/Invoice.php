<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use App\Observers\InvoiceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;

#[ObservedBy(InvoiceObserver::class)]
class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'payment_status' => PaymentStatusEnum::class
    ];

    public function invoice_details(): HasMany
    {
        return $this->hasMany(InvoiceDetails::class);
    }

    public function invoice_part_details: HasMany
    {
        return $this->hasMany(InvoicePartDetail::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function voucher(): HasOne
    {
        return $this->hasOne(Voucher::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // this function to prevent eager loading for get attributes
    public function newQuery($excludeDeleted = true)
    {
        return parent::newQuery($excludeDeleted)->with([
            'invoice_details',
            'invoice_part_details',
            'payments',
        ]);
    }

    public function getAmountAttribute()
    {
        $amount = 0;
        foreach ($this->invoice_details as $row) {
            $amount += $row->total;
        }
        foreach ($this->invoice_part_details as $row) {
            $amount += $row->total;
        }
        return $amount + $this->delivery - $this->discount;
    }

    public function getTotalPaidAmountAttribute()
    {
        $amount = 0;
        foreach ($this->payments as $row) {
            $amount += $row->amount;
        }
        return $amount;
    }

    public function getRemainingAmountAttribute()
    {
        return $this->amount - $this->total_paid_amount;
    }

    public function getServicesAmountAttribute()
    {
        $amount = 0;
        foreach ($this->invoice_details as $row) {
            if ($row->service->type == 'service') {
                $amount += $row->total;
            }
        }
        return $amount;
    }

    public function getPartsAmountAttribute()
    {
        $amount = 0;
        foreach ($this->invoice_details as $row) {
            if ($row->service->type == 'part') {
                $amount += $row->total;
            }
        }
        foreach ($this->invoice_part_details as $row) {
            $amount += $row->total;
        }
        return $amount;
    }

    public function getInternalPartsAmountAttribute()
    {
        $amount = 0;
        foreach ($this->invoice_details as $row) {
            if ($row->service->type == 'part') {
                $amount += $row->total;
            }
        }
        foreach ($this->invoice_part_details->where('type', 'internal') as $row) {
            $amount += $row->total;
        }
        return $amount;
    }

    public function getExternalPartsAmountAttribute()
    {
        $amount = 0;
        foreach ($this->invoice_part_details->where('type', 'external') as $row) {
            $amount += $row->total;
        }
        return $amount;
    }

    public function getServicesAmountAfterDiscountAttribute()
    {
        return $this->services_amount - $this->discount;
    }

    public function getCashAmountAttribute()
    {
        return $this->payments->where('method', 'cash')->sum('amount');
    }

    public function getKnetAmountAttribute()
    {
        return $this->payments->where('method', 'knet')->sum('amount');
    }

    public function computePaymentStatus()
    {
        if ($this->payments()->count() == 0) {
            return $this->amount > 0 ? 'pending' : 'free';
        } else {
            return $this->remaining_amount == 0 ? 'paid' : 'partially_paid';
        }
    }

    public function getCanApplyDiscountAttribute()
    {
        return
            Gate::allows('discount', $this)
            && $this->payments->count() == 0
            && !in_array(auth()->user()->title_id, Title::TECHNICIANS_GROUP);
    }

    public function getCanDeletedAttribute()
    {
        return
            Gate::allows('delete', $this)
            &&
            $this->payments->where('is_collected', true)->count() == 0
            &&
            $this->load('order:id')->order->invoices->count() > 1;
    }


    // Formaters

    public function getFormatedIdAttribute()
    {
        return str_pad($this->id, 8, '0', STR_PAD_LEFT);
    }

    public function getFormatedServicesAmountAttribute()
    {
        return $this->services_amount > 0 ? number_format($this->services_amount, 3) : '-';
    }

    public function getFormatedPartsAmountAttribute()
    {
        return $this->internal_parts_amount + $this->external_parts_amount > 0 ? number_format($this->internal_parts_amount + $this->external_parts_amount, 3) : '-';
    }

    public function getFormatedDiscountAmountAttribute()
    {
        return $this->discount > 0 ? number_format($this->discount, 3) : '-';
    }
    public function getFormatedServiceAmountAfterDiscountAttribute()
    {
        return $this->services_amount_after_discount > 0 ? number_format($this->services_amount_after_discount, 3) : '-';
    }

    public function getFormatedInternalPartsAmountAttribute()
    {
        return $this->internal_parts_amount > 0 ? number_format($this->internal_parts_amount, 3) : '-';
    }

    public function getFormatedExternalPartsAmountAttribute()
    {
        return $this->external_parts_amount > 0 ? number_format($this->external_parts_amount, 3) : '-';
    }

    public function getFormatedDeliveryAmountAttribute()
    {
        return $this->delivery > 0 ? number_format($this->delivery, 3) : '-';
    }

    public function getFormatedAmountAttribute()
    {
        return $this->amount > 0 ? number_format($this->amount, 3) : '-';
    }

    public function getFormatedCashAmountAttribute()
    {
        return $this->cash_amount > 0 ? number_format($this->cash_amount, 3) : '-';
    }

    public function getFormatedKnetAmountAttribute()
    {
        return $this->knet_amount > 0 ? number_format($this->knet_amount, 3) : '-';
    }

    public function getFormatedTotalPaidAmountAttribute()
    {
        return $this->total_paid_amount > 0 ? number_format($this->total_paid_amount, 3) : '-';
    }

    public function getFormatedRemainingAmountAttribute()
    {
        return $this->remaining_amount > 0 ? number_format($this->remaining_amount, 3) : '-';
    }

    public function getFormatedCreatedAtAttribute()
    {
        return '<span dir="ltr">' . ($this->created_at->format('d-m-Y | H:i')) . '</span>';
    }
}
