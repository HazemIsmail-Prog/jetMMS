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
    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->{'name_' . app()->getLocale()};
    }
}
