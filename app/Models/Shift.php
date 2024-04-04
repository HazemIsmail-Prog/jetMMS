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

    public function technicians()
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

    public function getFullNameAttribute()
    {
        return  $this->name . ' ' . __('messages.from') . ' ' . date('h:i', strtotime($this->start_time)) . ' ' . __('messages.to') . ' ' . date('h:i', strtotime($this->end_time));
    }
}
