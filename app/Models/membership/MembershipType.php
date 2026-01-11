<?php

namespace membership;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use user\CompadUser as User;
use event\Event;
use event\EventRegistration;

use Auth;
use Carbon;
use Illuminate\Support\Str;

class MembershipType extends Model
{
    protected $table = 'uq_membership_type';

    public static function boot()
    {
        parent::boot();

        static::updating(function($member)
        {
            $member->updated_by = Auth::id();
			$member->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($member)
        {
            $member->created_by = Auth::id();
			$member->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($member)
        {
            //
        });

        static::deleting(function($member)
        {
            
		});
    }
}
