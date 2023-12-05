<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketing extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilterWhenRequest($query, $filter)
    {
        return $query
            ->when($filter['name'], function ($q) use ($filter) {
                    $q->where('name', 'like', '%' . $filter['name'] . '%');
            })
            ->when($filter['phone'], function ($q) use ($filter) {
                    $q->where('phone', 'like', '%' . $filter['phone'] . '%');
            })
            ->when($filter['creators'], function ($q) use ($filter) {
                $q->whereIn('user_id', $filter['creators']);
            })
            ->when($filter['types'], function ($q) use ($filter) {
                $q->whereIn('type', $filter['types']);
            })
            ->when($filter['start_created_at'], function ($q) use ($filter) {
                $q->whereDate('created_at', '>=', $filter['start_created_at']);
            })
            ->when($filter['end_created_at'], function ($q) use ($filter) {
                $q->whereDate('created_at', '<=', $filter['end_created_at']);
            });
    }
}
