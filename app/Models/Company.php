<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getNameAttribute($value)
    {
        if (app()->getLocale() == 'ar') {
            return $this->name_ar ?? $this->name_en;
        } else {
            return $this->name_en ?? $this->name_ar;
        }
    }

    public function contracts() : HasMany {
        return $this->hasMany(CompanyContract::class,'company_id');
    }

    public function budgets() : HasMany {
        return $this->hasMany(CompanyBudget::class,'company_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
