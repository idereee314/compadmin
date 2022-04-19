<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;

class EventUser extends Model
{
    protected $table = 'uq_event_user';
    protected $primaryKey = 'id';
    protected $fillable = ['event_id', 'user_id'];

    public static function rules($id) 
    {
		return array(
            'event_id' => 'required',
            'user_id' => 'required'
		);
	}

    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }

    public function user()
    {
        return $this->belongsTo('user\CompadUser', 'user_id');
    }

	public static function boot()
    {
        parent::boot();

        static::updating(function($eventUser)
        {
            $eventUser->updated_by = Auth::id();
			$eventUser->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($eventUser)
        {
            $eventUser->created_by = Auth::id();
			$eventUser->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($eventUser)
        {
            //
        });

        static::deleting(function($academy)
        {

		});
    }
}
