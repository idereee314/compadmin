<?php

namespace organization;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;

class Organization extends Model
{
    protected $table = 'rti_organization';
    
    public static function boot()
    {
        parent::boot();

        static::updating(function($organization)
        {
            $organization->updated_by = Auth::id();
			$organization->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($organization)
        {
            $organization->created_by = Auth::id();
			$organization->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($organization)
        {
            //
        });

        static::deleting(function($organization)
        {

		});
    }
}
