<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Korridor\LaravelHasManyMerged\HasManyMergedRelation;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasManyMergedRelation;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function marketings()
    {
        return $this->hasMany(Marketing::class);
    }

    public function orders_technician()
    {
        return $this->hasMany(Order::class, 'technician_id');
    }

    public function orders_creator()
    {
        return $this->hasMany(Order::class, 'created_by');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->with('permissions');
    }

    public function messages()
    {
        return $this->hasManyMerged(Message::class, ['sender_user_id', 'receiver_user_id']);
    }

    public function permissions()
    {
        $permissionList = [];

        foreach ($this->roles as $role) {
            foreach ($role->permissions as $permission) {
                if (!in_array($permission->id, $permissionList)) {
                    $permissionList[] = $permission->id;
                }
            }
        }

        return $permissionList;
    }

    public function hasPermission($permission)
    {
        if ($this->permissions()) {
            if (in_array($permission, $this->permissions())) {
                return true;
            }
        }

        return false;
    }


    public function getNameAttribute()
    {
        if (app()->getLocale() == 'ar') {
            return $this->name_ar ?? $this->name_en;
        } else {
            return $this->name_en ?? $this->name_ar;
        }
    }

    public function getStatusAttribute()
    {
        return match ($this->active) {
            1 => __('messages.active'),
            0 => __('messages.inactive'),
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->active) {
            1 => 'green',
            0 => 'red',
        };
    }

    public function scopeActiveTechniciansPerDepartment($query, $department_id)
    {
        return $query =  $this
            ->where('active', true)
            ->whereIn('title_id', [10, 11])
            ->where('department_id', $department_id);
    }

    public function getUnreadMessagesAttribute()
    {
        return $this->messages()
            ->where('sender_user_id', $this->id)
            ->where('receiver_user_id', auth()->id())
            ->where('read', false)
            ->count();
    }

    public function getTotalUnreadMessagesAttribute()
    {
        return $this->messages()
            ->where('receiver_user_id', auth()->id())
            ->where('read', false)
            ->count();
    }

    public function getLatestMessageDateTimeAttribute()
    {
        $last_message =
            $this->messages()
            ->where(function($q){
                $q->
                where('receiver_user_id',auth()->id())
                ->orWhere('sender_user_id',auth()->id());
            })
            ->latest()
            ->first();
        return $last_message ? $last_message->created_at : '0';
    }

    // public function receivesBroadcastNotificationsOn(): string
    // {
    //     return 'users.' . $this->id;
    // }
}
