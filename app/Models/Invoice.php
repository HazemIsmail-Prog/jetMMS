<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use App\Observers\InvoiceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
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

    public function invoice_part_details(): HasMany
    {
        return $this->hasMany(InvoicePartDetail::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getServicesAmountAttribute(): float|null
    {
        return $this->loadSum(['invoice_details as servicesAmountSum' => function ($q) {
            $q->whereHas('service', function ($q) {
                $q->where('type', 'service');
            });
        }], DB::raw('quantity * price'))->servicesAmountSum;
    }

    public function getPartsAmountAttribute(): float|null
    {
        $amount = 0;

        $invoiceDetailsParts = $this->loadSum(['invoice_details as invoiceDetailsParts' => function ($q) {
            $q->whereHas('service', function ($q) {
                $q->where('type', 'part');
            });
        }], DB::raw('quantity * price'))->invoiceDetailsParts;

        $invoicePartDetails = $this->loadSum('invoice_part_details as invoicePartDetails', DB::raw('quantity * price'))->invoicePartDetails;

        $amount = $invoiceDetailsParts + $invoicePartDetails;

        return $amount;
    }

    public function getInternalPartsAmountAttribute(): float|null
    {
        $amount = 0;

        $invoiceDetailsParts = $this->loadSum(['invoice_details as invoiceDetailsParts' => function ($q) {
            $q->whereHas('service', function ($q) {
                $q->where('type', 'part');
            });
        }], DB::raw('quantity * price'))->invoiceDetailsParts;

        $invoiceInternalPartsDetails = $this->loadSum(['invoice_part_details as invoiceInternalPartsDetails' => function ($q) {
            $q->where('type', 'internal');
        }], DB::raw('quantity * price'))->invoiceInternalPartsDetails;

        $amount = $invoiceDetailsParts + $invoiceInternalPartsDetails;

        return $amount;
    }

    public function getExternalPartsAmountAttribute(): float|null
    {
        return $this->loadSum(['invoice_part_details as invoiceExternalPartsDetails' => function ($q) {
            $q->where('type', 'external');
        }], DB::raw('quantity * price'))->invoiceExternalPartsDetails;
    }

    public function getServicesAmountAfterDiscountAttribute(): float
    {
        return $this->services_amount - $this->discount;
    }

    public function getAmountAttribute(): float
    {
        return $this->services_amount_after_discount
        + $this->internal_parts_amount
        + $this->external_parts_amount
        + $this->delivery;
    }

    public function getCashAmountAttribute(): float|null
    {
        return $this->loadSum(['payments as cash' => function ($q) {
            $q->where('method', 'cash');
        }], 'amount')->cash;
    }

    public function getKnetAmountAttribute(): float|null
    {
        return $this->loadSum(['payments as knet' => function ($q) {
            $q->where('method', 'knet');
        }], 'amount')->knet;
    }

    public function getTotalPaidAmountAttribute(): float|null
    {
        return $this->loadSum('payments as total','amount')->total;
    }

    public function getRemainingAmountAttribute(): float
    {
        return abs(round($this->amount - $this->total_paid_amount,3)) ;
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
        // Eager load necessary relationships with count
        $this->loadCount(['payments' => function ($query) {
            $query->where('is_collected', true);
        }]);

        $order_invoices_count = $this->where('order_id', $this->order_id)->count();

        // Check if the gate allows deletion, if payments are collected, and if there are more than one invoice for the order
        return Gate::allows('delete', $this) &&
            $this->payments_count === 0 &&
            $order_invoices_count > 1;
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
