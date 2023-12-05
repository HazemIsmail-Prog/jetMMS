<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function brand() : BelongsTo {
        return $this->belongsTo(CarBrand::class,'car_brand_id');
    }

    public function type() : BelongsTo {
        return $this->belongsTo(CarType::class,'car_type_id');
    }

    public function driver() : BelongsTo {
        return $this->belongsTo(User::class,'driver_id');
    }

    public function technician() : BelongsTo {
        return $this->belongsTo(User::class,'technician_id');
    }

    public function actions() : HasMany {
        return $this->hasMany(CarAction::class);
    }
}
