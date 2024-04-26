<?php

namespace App\Models;

use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;

#[ObservedBy(OrderObserver::class)]
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'estimated_start_date' => 'date:Y-m-d',
        'created_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function rating(): HasOne
    {
        return $this->hasOne(Rating::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function phone(): BelongsTo
    {
        return $this->belongsTo(Phone::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }


    public function department()
    {
        return $this->belongsTo(Department::class);
    }









    public function statuses()
    {
        return $this->hasMany(OrderStatus::class);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->invoices->sum('remaining_amount');
    }

    public function latest_status()
    {
        return $this->hasOne(OrderStatus::class)->orderByDesc('id');
    }

    public function latest_arrived()
    {
        return $this->hasOne(OrderStatus::class)->where('status_id', 7)->orderByDesc('id');
    }

    public function latest_received()
    {
        return $this->hasOne(OrderStatus::class)->where('status_id', 3)->orderByDesc('id');
    }

    public function getArriveToCompleteAttribute()
    {
        if (isset($this->latest_arrived->created_at)) {
            return $this->completed_at->diff($this->latest_arrived->created_at)->format('%H:%I');
        }

        return '-';
    }

    public function getReceiveToCompleteAttribute()
    {
        return $this->completed_at->diff($this->latest_received->created_at)->format('%H:%I');
    }

    public function getWhatsappMessageAttribute()
    {

        // 'https://api.whatsapp.com/send?phone=447777333333&text=Message%0awith%0anewlines'

        $line1 = '*مسك الدار للمقاولات العامة للمباني*';
        $line2 = '%0a';
        $line3 = 'تم تنفيذ طلبكم رقم ' . $this->getFormatedIdAttribute();;
        $line4 = '%0a';
        $line5 = 'يمكنك تقييم الطلب وتحميل الفواتير من خلال الرابط التالي';
        $line6 = '%0a';
        $line7 = '%0a';
        $welcomeMessage = $line1 . $line2 . $line3 . $line4 . $line5 . $line6 . $line7;
        // $number = '96599589018';
        $number = '965' . $this->phone->number;
        $encryptedOrderId = route('customer.page', encrypt($this->id));
        return 'https://api.whatsapp.com/send?phone=' . $number . '&text=' . $welcomeMessage . $encryptedOrderId;
    }


    public function getCanViewOrderInvoicesAttribute()
    {
        return  Gate::allows('view_order_invoices', $this) && in_array($this->status_id, [Status::ARRIVED, Status::COMPLETED]);
    }

    public function getCanSendSurveyAttribute()
    {
        return  Gate::allows('send_survey', $this) && $this->status_id == Status::COMPLETED;
    }

    public function getCanHoldOrderAttribute()
    {
        return  Gate::allows('hold_order', $this) && $this->status_id != Status::ON_HOLD;
    }

    public function getInProgressAttribute()
    {
        return in_array($this->status_id, [Status::RECEIVED, Status::ARRIVED]);
    }





    // public function getInvoicesCountAttribute()
    // {
    //     return $this->invoices()->count();
    // }

    public function scopeFilterWhenRequest($query, $filter)
    {
        return $query
            ->when($filter['order_number'], function ($q) use ($filter) {
                $q->where('id', $filter['order_number']);
            })
            ->when($filter['customer_id'], function ($q) use ($filter) {
                $q->where('customer_id', $filter['customer_id']);
            })
            ->when($filter['customer_name'], function ($q) use ($filter) {
                $q->whereHas('customer', function ($q2) use ($filter) {
                    $q2->where('name', 'like', '%' . $filter['customer_name'] . '%');
                });
            })
            ->when($filter['customer_phone'], function ($q) use ($filter) {
                $q->whereHas('phone', function ($q2) use ($filter) {
                    $q2->where('number', 'like', '%' . $filter['customer_phone'] . '%');
                });
            })
            ->when($filter['areas'], function ($q) use ($filter) {
                $q->whereHas('address', function ($q2) use ($filter) {
                    $q2->whereIn('area_id', $filter['areas']);
                });
            })
            ->when($filter['block'], function ($q) use ($filter) {
                $q->whereHas('address', function ($q2) use ($filter) {
                    $q2->where('block', 'like', $filter['block']);
                });
            })
            ->when($filter['street'], function ($q) use ($filter) {
                $q->whereHas('address', function ($q2) use ($filter) {
                    $q2->where('street', 'like', $filter['street']);
                });
            })
            ->when($filter['creators'], function ($q) use ($filter) {
                $q->whereIn('created_by', $filter['creators']);
            })
            ->when($filter['statuses'], function ($q) use ($filter) {
                $q->whereIn('status_id', $filter['statuses']);
            })
            ->when($filter['tags'], function ($q) use ($filter) {
                $q->whereIn('tag', $filter['tags']);
            })
            ->when($filter['technicians'], function ($q) use ($filter) {
                $q->whereIn('technician_id', $filter['technicians']);
            })
            ->when($filter['departments'], function ($q) use ($filter) {
                $q->whereIn('department_id', $filter['departments']);
            })
            ->when($filter['start_created_at'], function ($q) use ($filter) {
                $q->whereDate('created_at', '>=', $filter['start_created_at']);
            })
            ->when($filter['end_created_at'], function ($q) use ($filter) {
                $q->whereDate('created_at', '<=', $filter['end_created_at']);
            })
            ->when($filter['start_completed_at'], function ($q) use ($filter) {
                $q->whereDate('completed_at', '>=', $filter['start_completed_at']);
            })
            ->when($filter['end_completed_at'], function ($q) use ($filter) {
                $q->whereDate('completed_at', '<=', $filter['end_completed_at']);
            });
    }

    // Formatters
    public function getFormatedIdAttribute()
    {
        return str_pad($this->id, 8, '0', STR_PAD_LEFT);
    }

    public function getFormatedCreatedAtAttribute()
    {
        return '<span dir="ltr">' . ($this->created_at->format('d-m-Y | H:i')) . '</span>';
    }

    public function getFormatedCompletedAtAttribute()
    {
        return '<span dir="ltr">' . ($this->completed_at ? $this->completed_at->format('d-m-Y | H:i') : '-') . '</span>';
    }

    public function getFormatedEstimatedStartDateAttribute()
    {
        return '<span dir="ltr">' . ($this->estimated_start_date->format('d-m-Y')) . '</span>';
    }

    public function getFormatedRemainingAmountAttribute()
    {
        return $this->remaining_amount > 0 ? number_format($this->remaining_amount, 3) : '-';
    }

    public function getFormatedOrderIdAttribute()
    {
        return str_pad($this->id, 8, '0', STR_PAD_LEFT);
    }
}
