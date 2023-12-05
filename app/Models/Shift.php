<?php

namespace App\Models;

use App\Casts\TimeCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;


class Shift extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
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
