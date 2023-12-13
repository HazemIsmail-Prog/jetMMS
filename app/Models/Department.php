<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Department extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    public function technicians()
    {
        return $this->hasMany(User::class)->whereIn('title_id', [10, 11]);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
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
