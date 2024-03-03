<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Status extends Model
{
    use HasFactory;

    protected $table = 'statuses';

    const CREATED = 1;
    const DESTRIBUTED = 2;
    const RECEIVED = 3;
    const COMPLETED = 4;
    const ON_HOLD = 5;
    const CANCELLED = 6;
    const ARRIVED = 7;

    protected $guarded = [];

    public function getNameAttribute($value)
    {
        if (App::getLocale() == 'ar') {
            return $this->name_ar ?? $this->name_en;
        } else {
            return $this->name_en ?? $this->name_ar;
        }
    }
}
