<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherIncomeCategory extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = [
        'can_create',
        'can_edit',
        'can_delete',
    ];
    public function getCanCreateAttribute()
    {
        return auth()->user()->hasPermission('other_income_categories_create');
    }

    public function getCanEditAttribute()
    {
        return auth()->user()->hasPermission('other_income_categories_edit');
    }

    public function getCanDeleteAttribute()
    {
        return auth()->user()->hasPermission('other_income_categories_delete');
    }

}
