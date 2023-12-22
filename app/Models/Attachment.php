<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'alertable' => 'boolean',
        'expirationDate' => 'date:Y-m-d',
    ];

    public function getDescriptionAttribute($value)
    {
        if (app()->getLocale() == 'ar') {
            return $this->description_ar ?? $this->description_en;
        } else {
            return $this->description_en ?? $this->description_ar;
        }
    }

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getFullPathAttribute()
    {
        return 'https://miskalddartestbucket.s3.amazonaws.com/' . $this->file;
    }
}
