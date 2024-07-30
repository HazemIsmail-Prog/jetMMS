<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PhoneDeviceAction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'time' => 'date:H:i',
    ];

    public function from(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_id');
    }
    public function to(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    public function current_holder() : HasOne
    {
        return $this->hasOne(User::class, 'to_id')->latest();
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(PhoneDevice::class,'phone_device_id');
    }
}
