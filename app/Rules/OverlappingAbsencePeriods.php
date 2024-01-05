<?php

namespace App\Rules;

use App\Models\Absence;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class OverlappingAbsencePeriods implements DataAwareRule,ValidationRule
{
    protected $ownerId = [];
    protected $start_date = [];
    protected $end_date = [];
    protected $current_absence_id = '';

    public function setData(array $data): static
    {
        $this->ownerId = $data['employee_id'];
        $this->current_absence_id = $data['id'];
        $this->start_date = $data['start_date'];
        $this->end_date = $data['end_date'];
        return $this;
    }
    
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $singleDateCheck = Absence::query()
            ->whereNot('id', $this->current_absence_id)
            ->where('employee_id', $this->ownerId)
            ->where(function ($query) use ($value) {
                $query->whereDate('start_date', '<=', $value)->whereDate('end_date', '>=', $value);
            })->count();

        $intersectionCheck = Absence::query()
            ->whereNot('id', $this->current_absence_id)
            ->where('employee_id', $this->ownerId)
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])->orWhereBetween('end_date', [$this->start_date, $this->end_date]);
            })->count();

        if ($singleDateCheck > 0) {
            $fail('The :attribute overlaps an old absence');
        }
        if ($intersectionCheck > 0) {
            $fail('This absence peiod has intersection absence');
        }    }
}
