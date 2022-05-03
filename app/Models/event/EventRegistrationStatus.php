<?php

namespace event;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;

class EventRegistrationStatus extends Model
{
    protected $table = 'uq_event_registration_status';
    protected $fillable = ['event_registration_id', 'status', 'changed_by', 'changed_at'];
    public $timestamps = false;

    public static $rules = array(
        'event_registration_id' => 'required',
        'status' => 'required'
    );

    public function eventRegistration()
    {
        return $this->belongsTo('event\EventRegistration', 'event_registration_id');
    }

    public function changedBy()
    {
        return $this->belongsTo('user\CompadUser', 'changed_by');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($regStatus)
        {
            $regStatus->changed_by = Auth::id();
			$regStatus->changed_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($regStatus)
        {
            $eventReg = $regStatus->eventRegistration;
            $eventReg->status = $regStatus->status;
            $eventReg->save();
        });

        static::deleting(function($status)
        {
            $eventReg = @$status->eventRegistration;
            $prevStatus = @$eventReg->statuses->where('id', '!=', $status->id)->orderBy('changed_at', 'desc')->first();

            $eventReg->status = @$prevStatus ? $prevStatus->status : @Config::get('smart.event_registration_statu')['created'];
            $eventReg->save();
		});
    }
}
