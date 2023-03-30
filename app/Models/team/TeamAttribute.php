<?php

namespace team;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use user\CompadUser as User;
use event\Event;
use event\EventRegistration;
use event\EventTeamRegistration;

use Auth;
use Carbon;
use Illuminate\Support\Str;

class TeamAttribute extends Model
{
    protected $table = 'uq_member_attr_value';
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function team()
    {
        return $this->belongsTo('team/Team', 'team_id');
    }

    public function attribute()
    {
        return $this->belongsTo('attribute\Attribute', 'attribute_id');
    }

    public function eventRegistration()
    {
        return $this->hasMany(EventRegistration::class, 'member_id');
    }

    public function eventTeamRegistration()
    {
        return $this->hasMany(EventTeamRegistration::class, 'member_id');
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function($item)
        {
            $item->updated_by = Auth::id();
			$item->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($item)
        {
            $item->created_by = Auth::id();
			$item->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($item)
        {
            //
        });

        static::deleting(function($item)
        {
            //
		});
    }
}
