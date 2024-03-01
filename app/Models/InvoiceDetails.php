<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    use HasFactory;

    protected $guarded = [];

    // this function to prevent eager loading for get attributes
    public function newQuery($excludeDeleted = true)
    {
        return parent::newQuery($excludeDeleted)->with([
            'service',
        ]);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }
}
