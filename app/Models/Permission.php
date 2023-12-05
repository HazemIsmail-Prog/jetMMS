<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Permission extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getDescriptionAttribute()
    {
        if (App::getLocale() == 'ar') {
            return $this->desc_ar ?? $this->desc_en;
        } else {
            return $this->desc_en ?? $this->desc_ar;
        }
    }

    public function getSectionNameAttribute()
    {
        $section_name_en = ucwords(str_replace('_', ' ', $this->section_name_en));
        if (App::getLocale() == 'ar') {
            return $this->section_name_ar ?? $section_name_en;
        } else {
            return $this->section_name_en ? $section_name_en : $this->section_name_ar;
        }
    }
}
