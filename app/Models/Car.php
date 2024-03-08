<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Car extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'insurance_expiration_date' => 'date:Y-m-d',
        'adv_expiration_date' => 'date:Y-m-d',
        'has_installment' => 'boolean',
        'active' => 'boolean',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CarType::class, 'car_type_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function car_actions(): HasMany
    {
        return $this->hasMany(CarAction::class, 'car_id');
    }

    public function car_services(): HasMany
    {
        return $this->hasMany(CarService::class, 'car_id');
    }

    public function latest_car_action(): HasOne
    {
        return $this->hasOne(CarAction::class, 'car_id')->orderBy('date', 'desc')->orderBy('time', 'desc');
    }
}
