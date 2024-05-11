<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Title extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    const TECHNICIANS_GROUP = [
        10, // فني
        11, // مراقب
        26, // فني نسبة
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
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
