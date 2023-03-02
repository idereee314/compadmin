<?php

namespace sport;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;

class Sport extends Model
{
    protected $table = 'uq_sport';

    public function config()
    {
        return $this->hasOne('event\EventConfig', 'sport_id');
    }

    public function eventRegistration()
    {
        return $this->hasOne('event\EventConfig', 'sport_id');
    }

    
    public static function boot()
    {
        parent::boot();

        static::updating(function($sport)
        {
            $sport->updated_by = Auth::id();
			$sport->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($sport)
        {
            $sport->created_by = Auth::id();
			$sport->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($sport)
        {
            //
        });

        static::deleting(function($sport)
        {

		});

        
    }
}
