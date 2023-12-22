<?php

namespace App\Models;

use App\Enums\ServiceTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'type' => ServiceTypeEnum::class
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function getNameAttribute($value)
    {
        if (App::getLocale() == 'ar') {
            return $this->name_ar ?? $this->name_en;
        } else {
            return $this->name_en ?? $this->name_ar;
        }
    }
}
