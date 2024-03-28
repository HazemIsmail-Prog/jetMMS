<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function parent() : BelongsTo {
        return $this->belongsTo(Account::class,'account_id');
    }
    
    public function child_accounts() : HasMany {
        return $this->hasMany(Account::class,'account_id')->with('child_accounts');
    }

    public function voucher_details() : HasMany {
        return $this->hasMany(VoucherDetail::class);
    }

    public function getNameAttribute()
    {
        if (app()->getLocale() == 'ar') {
            return $this->name_ar ?? $this->name_en;
        } else {
            return $this->name_en ?? $this->name_ar;
        }
    }

    // Formatters

    public function getFormatedDebitSumAttribute()
    {
        return $this->voucher_details->sum('debit') == 0 ? '-' : number_format($this->voucher_details->sum('debit'), 3);
    }

    public function getFormatedCreditSumAttribute()
    {
        return $this->voucher_details->sum('credit') == 0 ? '-' : number_format($this->voucher_details->sum('credit'), 3);
    }
}
