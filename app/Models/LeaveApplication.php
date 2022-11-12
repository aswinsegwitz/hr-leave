<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'date',
    ];

    protected $guarded = [];

    public function applier()
    {
        return $this->hasOne(User::class, 'id', 'applier_user_id');
    }

    public function authorizer()
    {
        return $this->hasOne(User::class, 'id', 'authorizer_user_id');
    }

    public function type()
    {
        return $this->hasOne(LeaveType::class, 'id', 'leave_type_id');
    }

    public function getStartDateAttribute($value)
    {
        return (new Carbon($value))->toFormattedDateString();
    }
    public function getEndDateAttribute($value)
    {
        return ($value) ? (new Carbon($value))->toFormattedDateString() : $value;
    }

    public function getCreatedAtAttribute($value)
    {
        return (new Carbon($value))->toFormattedDateString();
    }

    public function getDurationAttribute()
    {
        return (new Carbon($this->end_date))->diffInDays(new Carbon($this->start_date))+1;
    }


}
