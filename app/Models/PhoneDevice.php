<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PhoneDevice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function device_actions(): HasMany
    {
        return $this->hasMany(PhoneDeviceAction::class, 'phone_device_id');
    }

    public function latest_device_action(): HasOne
    {
        return $this->hasOne(PhoneDeviceAction::class, 'phone_device_id')->orderBy('date', 'desc')->orderBy('time', 'desc');
    }
}
