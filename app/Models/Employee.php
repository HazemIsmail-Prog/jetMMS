<?php

namespace App\Models;

use App\Enums\EmployeeStatusEnum;
use App\Enums\LeaveTypeEnum;
use Carbon\Carbon;
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

    public function absences() : HasMany {
        return $this->hasMany(Absence::class);
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

    // this function to prevent eager loading for get attributes
    public function newQuery($excludeDeleted = true)
    {
        return parent::newQuery($excludeDeleted)->with([
            'leaves',
            'increases',
            'absences',
        ]);
    }

    public function getSalaryAttribute() {
        return $this->increases->sum('amount') + $this->startingSalary;
    }

    public function getSalaryPerDayAttribute() {
        return $this->salary / 26;
    }


    public function getPaidLeavesSumAttribute() {
        $days_sum = 0;
        foreach ($this->leaves->where('type', LeaveTypeEnum::ANNUAL) as $leave){
            $days_sum += $leave->leave_days_count;
        }
        return $days_sum;
    }

    public function getAbsenceSumAttribute() {
        $days_sum = 0;
        foreach ($this->absences as $abcence){
            $days_sum += $abcence->abcence_days_count;
        }
        return $days_sum;
    }

    public function getUnpaidLeavesSumAttribute() {
        $days_sum = 0;
        foreach ($this->leaves->where('type', LeaveTypeEnum::UNPAID) as $leave){
            $days_sum += $leave->leave_days_count;
        }
        return $days_sum;
    }

    public function getNetWorkingDaysAttribute($date = null)
    {
        $date = $date ?? today();

        $join_date = Carbon::parse($this->joinDate ?? null);
        $today = Carbon::parse($date ?? null);

        if ($join_date > $today) {
            return 0;
        }

        $diffInDays = $today->diffInDays($join_date);
        $netWorkingDays = $diffInDays - $this->getUnpaidLeavesSumAttribute() - $this->getAbsenceSumAttribute() + 1;

        return $netWorkingDays;
    }

    public function getLeaveDaysBalanceAttribute() {
        $total_leave_days = $this->getNetWorkingDaysAttribute() / 365 * 30;
        return $total_leave_days - ($this->getPaidLeavesSumAttribute() + $this->startingLeaveBalance);
    }

    public function getLeaveBalanceAmountAttribute($date = null)
    {
        $date = $date ?? today();

        return $this->salary / 26 * $this->getLeaveDaysBalanceAttribute($date);
    }

    public function getIndemnityAttribute($date = null)
    {
        $indemnity = 0;
        $day_salary = $this->salary /26;;

        $date = $date ?? today();

        if ($this->getNetWorkingDaysAttribute($date) <= 1825) // less then 5 years
        {
            $indemnity = ($this->getNetWorkingDaysAttribute() / 365 * 15) * $this->salary / 26;
        }

        if ($this->getNetWorkingDaysAttribute($date) > 1825) // more than 5 years
        {
            $first_5_years = 75 * $day_salary; //346.125
            $remaining_days = $this->getNetWorkingDaysAttribute($date) - 1825;  
            $more_than_5_years = $remaining_days / 365 * $day_salary * 30;
            $indemnity = $first_5_years + $more_than_5_years;
        }


        return $indemnity;
    }


    // Formatters

    public function getFormatedJoinDateAttribute() {
        return '<span dir="ltr">' . ($this->joinDate->format('d-m-Y')) . '</span>';
    }
    public function getFormatedStatusAttribute() {
        return '<div class="text-'. $this->status->color().'-400">'. $this->status->title() . '</div>';
    }

    public function getFormatedLeaveDaysBalanceAttribute() {
        return number_format($this->LeaveDaysBalance, 2);
    }
    public function getFormatedLeaveBalanceAmountAttribute() {
        return number_format($this->LeaveBalanceAmount, 3);
    }
    public function getFormatedIndemnityAttribute() {
        return number_format($this->Indemnity, 3);
    }
    public function getFormatedSalaryAttribute() {
        return number_format($this->salary, 3);
    }

}
