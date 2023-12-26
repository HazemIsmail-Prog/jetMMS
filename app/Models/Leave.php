<?php

namespace App\Models;

use App\Enums\LeaveStatusEnum;
use App\Enums\LeaveTypeEnum;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Leave extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'type' => LeaveTypeEnum::class,
        'status' => LeaveStatusEnum::class,
    ];

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function getLeaveDaysCountAttribute()
    {

        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);

        // Create a date range from the start date to the end date
        $period = CarbonPeriod::create($startDate, $endDate);

        // Initialize a counter variable to keep track of the days excluding Fridays
        $daysExcludingFridays = 0;

        // Loop through each date in the range and exclude Fridays
        foreach ($period as $date) {
            if ($date->dayOfWeek !== Carbon::FRIDAY) {
                $daysExcludingFridays++;
            }
        }

        return $daysExcludingFridays;
    }
}
