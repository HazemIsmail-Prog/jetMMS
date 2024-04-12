<?php

namespace App\Models;

use App\Enums\VoucherTypeEnum;
use App\Observers\VoucherObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(VoucherObserver::class)]
class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'type' => VoucherTypeEnum::class
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

    public function getCastedTypeTitleAttribute() {
        return $this->type->title();
    }
    public function getCastedTypeColorClassesAttribute() {
        return $this->type->color();
    }

    public function getFormatedDateAttribute()
    {
        return '<span dir="ltr">' . ($this->date->format('d-m-Y')) . '</span>';
    }

    public function getFormatedAmountAttribute()
    {
        return $this->amount > 0 ? number_format($this->amount, 3) : '-';
    }
}
