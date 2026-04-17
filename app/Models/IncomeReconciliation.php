<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeReconciliation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function incomeInvoice()
    {
        return $this->belongsTo(IncomeInvoice::class);
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'income_reconciliation_id');
    }
}
