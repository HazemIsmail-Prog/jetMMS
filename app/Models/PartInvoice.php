<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartInvoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }


    public function contact(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contact_id');
    }

    // Formatters

    public function getFormatedCostAmountAttribute()
    {
        return $this->cost_amount > 0 ? number_format($this->cost_amount, 3) : '-';
    }

    public function getFormatedSalesAmountAttribute()
    {
        return $this->sales_amount > 0 ? number_format($this->sales_amount, 3) : '-';
    }
}
