<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Department extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    public function technicians(): HasMany
    {
        return $this->hasMany(User::class)->whereIn('title_id', Title::TECHNICIANS_GROUP);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function incomeAccount() : BelongsTo {
        return $this->belongsTo(Account::class, 'income_account_id');
    }

    public function costAccount() : BelongsTo {
        return $this->belongsTo(Account::class, 'cost_account_id');
    }

    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->{'name_' . app()->getLocale()};
    }
}
