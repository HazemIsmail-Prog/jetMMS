<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarAction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'time' => 'date:H:i',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class,'driver_id');
    }
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function getFuelAttribute($val) {

        $fuel = match ($val) {
            0 => __('messages.empty'),
            1 => '1/4',
            2 => '1/2',
            3 => '3/4',
            4 => __('messages.full'),
        };

        return $fuel;

    }
}
