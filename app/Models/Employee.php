<?php

namespace App\Models;

use App\Enums\EmployeeStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'joinDate' => 'date:Y-m-d',
        'status' => EmployeeStatusEnum::class,
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function company() : BelongsTo {
        return $this->belongsTo(Company::class);
    }

    public function leaves() : HasMany {
        return $this->hasMany(Leave::class);
    }

    public function increases() : HasMany {
        return $this->hasMany(Increase::class);
    }

    public function salaryActions() : HasMany {
        return $this->hasMany(SalaryAction::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
