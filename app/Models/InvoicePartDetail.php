<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePartDetail extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    
}
