<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function voucher() : BelongsTo {
        return $this->belongsTo(Voucher::class);
    }
    public function account() : BelongsTo {
        return $this->belongsTo(Account::class);
    }
    public function contact() : BelongsTo {
        return $this->belongsTo(User::class,'user_id');
    }
    public function cost_center() : BelongsTo {
        return $this->belongsTo(CostCenter::class);
    }

    // Formatters

    public function getFormatedDebitAttribute()
    {
        return $this->debit == 0 ? '-' : number_format($this->debit, 3);
    }

    public function getFormatedCreditAttribute()
    {
        return $this->credit == 0 ? '-' : number_format($this->credit, 3);
    }

}
