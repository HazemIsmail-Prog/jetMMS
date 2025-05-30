<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

}
