<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    use HasFactory;

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
}
