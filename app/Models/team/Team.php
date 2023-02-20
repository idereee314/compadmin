<?php

namespace team;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;

class Team extends Model
{
    protected $table = 'uq_team';

    public function config()
    {
        return $this->hasOne('event\EventConfig', 'team_id');
    }

    public function athlete()
    {
        return $this->belongsTo('member\TeamMember', 'id');
    }


    
    public static function boot()
    {
        parent::boot();

        static::updating(function($team)
        {
            $team->updated_by = Auth::id();
			$team->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($team)
        {
            $team->created_by = Auth::id();
			$team->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($team)
        {
            //
        });

        static::deleting(function($team)
        {

		});

        
    }
}
