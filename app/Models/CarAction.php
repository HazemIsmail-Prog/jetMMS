<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CarAction extends Model
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

    public function current_driver() : HasOne
    {
        return $this->hasOne(User::class, 'to_id')->latest();
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
