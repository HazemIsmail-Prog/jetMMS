<?php

namespace App\Models;

use App\Enums\SalaryActionStatusEnum;
use App\Enums\SalaryActionTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SalaryAction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'type' => SalaryActionTypeEnum::class,
        'status' => SalaryActionStatusEnum::class,

    ];

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
