<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    protected $appends = ['formated_created_at_date', 'formated_created_at_time'];

    public function getFormatedCreatedAtDateAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }

    public function getFormatedCreatedAtTimeAttribute()
    {
        return $this->created_at->format('H:i');
    }
    
    
}
