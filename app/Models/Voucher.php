<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    public function voucherDetails() : HasMany {
        return $this->hasMany(VoucherDetail::class);
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class,'created_by');
    }

    public function getAmountAttribute() {
        return $this->voucherDetails()->sum('credit');
    }

    // Formatters

    public function getFormatedDateAttribute()
    {
        return '<span dir="ltr">' . ($this->date->format('d-m-Y')) . '</span>';
    }

    public function getFormatedAmountAttribute()
    {
        return $this->amount > 0 ? number_format($this->amount, 3) : '-';
    }
}
