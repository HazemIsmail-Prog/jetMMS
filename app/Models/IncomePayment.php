<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomePayment extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    protected $appends = [
        'translated_method',
        'formatted_date',
        'can_edit',
        'can_delete',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFormattedDateAttribute()
    {
        return $this->date->format('d-m-Y');
    }

    public function getCanEditAttribute()
    {
        return auth()->user()->hasPermission('income_invoices_payments_edit');
    }

    public function getCanDeleteAttribute()
    {
        return auth()->user()->hasPermission('income_invoices_payments_delete');
    }

    public function getTranslatedMethodAttribute()
    {
        return __('messages.' . strtolower($this->method));
    }

    public function incomeInvoice()
    {
        return $this->belongsTo(IncomeInvoice::class);
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'income_payment_id');
    }
}
