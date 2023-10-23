<?php

namespace event;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;
use Config;

class EventRefundRequest extends Model
{
    protected $table = 'uq_event_refund_request';
    protected $primaryKey = 'id';

    public static function rules($id) 
    {
		return array(
            'academy_id' => 'required',
		);
	}

    public function registration()
    {
        return $this->hasMany('event\EventRegistration', 'event_id')->orderBy('uq_event_registration.created_at', 'desc');
    }

    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }

    public function member()
    {
        return $this->belongsTo('member\Member', 'member_id');
    }

    public function academy()
    {
        return $this->belongsTo('academy\Academy', 'academy_id');
    }

    public function sport()
    {
        return $this->belongsTo('sport\Sport', 'sport_id');
    }
    
    public static function boot()
    {
        parent::boot();
        
        static::updating(function($eventRefundRequest)
        {
            $eventRefundRequest->updated_by = Auth::id();
			$eventRefundRequest->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });
        
        static::creating(function($eventRefundRequest)
        {
            $eventRefundRequest->status = @Config::get('smart.event_refund_request_status')['requested'];
            $eventRefundRequest->created_by = Auth::id();
			$eventRefundRequest->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($eventRefundRequest)
        {
            //
        });

        static::deleting(function($member)
        {

		});
    }
}
