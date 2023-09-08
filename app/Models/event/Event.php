<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;

class Event extends Model
{
    protected $table = 'rti_event';
    protected $primaryKey = 'id';

    public function pictures()
    {       
        return $this->hasMany('event\EventPicture', 'event_id');
    }

    public function picturesMobileCover()
    {
		return $this->pictures()->join('rtc_picture_type', 'rtc_picture_type.id', '=', 'rti_event_picture.picture_type_id')->where('rtc_picture_type.code', 'event_cover_mobile')->selectRaw('rti_event_picture.*, rtc_picture_type.id, rtc_picture_type.dir_url');
	}

    public function registration()
    {
        return $this->hasMany('event\EventRegistration', 'event_id')->orderBy('uq_event_registration.created_at', 'desc');
    }

    public function organization()
    {
        return $this->belongsToMany('organization\OrganizationEvent', 'event_id');
    }

    public function members()
    {
        return $this->belongsToMany('member\Member', 'uq_event_registration', 'event_id', 'member_id')->orderBy('uq_event_registration.created_at', 'desc');
    }

    public function config()
    {
        return $this->hasOne('event\EventConfig', 'event_id');
    }

    public function entries()
    {
        return $this->hasMany('reference\EventEntries', 'event_id')->orderBy('gender_code')->orderBy('name');
    }

    public function users()
    {
        return $this->belongsToMany('user\CompadUser', 'uq_event_user', 'event_id', 'user_id');
    }

    public function eventUsers()
    {
        return $this->hasMany('event\EventUser', 'event_id');
    }

    public function eventToplistPoint()
    {
        return $this->hasMany('reference\EventToplistPoint', 'event_id');
    }

	public static function boot()
    {
        parent::boot();    
    }
}
