<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeInvoice extends Model
{
    use HasFactory;

    protected $appends = [
        'can_edit',
        'can_delete',
        'can_view_payments',
        'can_create_payments',
        'can_view_attachments',
        'can_create_attachment',
        'can_update_attachment',
        'can_delete_attachment',
        'formatted_date',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // get formatted date
    public function getFormattedDateAttribute()
    {
        return $this->date ? $this->date->format('d-m-Y') : null;
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'income_invoice_id');
    }

    public function payments()
    {
        return $this->hasMany(IncomePayment::class, 'income_invoice_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function getCanViewPaymentsAttribute()
    {
        return auth()->user()->hasPermission('income_invoices_payments_menu');
    }
    
    public function getCanCreatePaymentsAttribute()
    {
        return auth()->user()->hasPermission('income_invoices_payments_create');
    }


    public function getCanEditAttribute()
    {
        return auth()->user()->hasPermission('income_invoices_edit');
    }

    public function getCanDeleteAttribute()
    {
        return auth()->user()->hasPermission('income_invoices_delete');
    }

    public function getCanViewAttachmentsAttribute()
    {
        return auth()->user()->hasPermission('income_invoices_attachments_menu');
    }

    public function getCanCreateAttachmentAttribute()
    {
        return auth()->user()->hasPermission('income_invoices_attachments_create');
    }

    public function getCanUpdateAttachmentAttribute()
    {
        return auth()->user()->hasPermission('income_invoices_attachments_edit');
    }

    public function getCanDeleteAttachmentAttribute()
    {
        return auth()->user()->hasPermission('income_invoices_attachments_delete');
    }
}
