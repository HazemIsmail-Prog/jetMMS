<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getFullLogoPathAttribute()
    {
        return 'https://' . config('filesystems.disks.s3.bucket') . '.s3.amazonaws.com/' . $this->logo;
    }

    public function getFullFaviconPathAttribute()
    {
        return 'https://' . config('filesystems.disks.s3.bucket') . '.s3.amazonaws.com/' . $this->favicon;
    }
}
