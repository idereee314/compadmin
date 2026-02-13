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

class MembershipAcademy extends Model
{
    protected $table = 'uq_membership_academy';
    protected $primaryKey = 'id';
    protected $fillable = ['membership_type_id','academy_id','start_date','end_date','description','created_by','updated_by'];

    public static function rules($id)
    {
        return array(
			'membership_type_id' => 'required',
            'academy_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
		);
    }

    public function academy()
    {
        return $this->belongsTo('academy\Academy', 'academy_id', 'id');
    }
    
    public function membershipType()
    {
        return $this->belongsTo('membership\MembershipType', 'membership_type_id', 'id');
    }

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
