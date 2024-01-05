<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Absence extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
    ];

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function getAbsenceDaysCountAttribute()
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
