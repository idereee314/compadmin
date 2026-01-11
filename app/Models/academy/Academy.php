<?php

namespace academy;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use user\CompadUser as User;
use organization\Organization;

use Auth;
use Carbon;

class Academy extends Model
{
    protected $table = 'uq_academy';
    
    public static function rules($id) 
    {
		return array(
            //'organization_id' => 'required',
            'name' => 'required',
            'name_en' => 'required',
		);
	}

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function sport()
    {
        return $this->belongsTo('sport\Sport', 'sport_id', 'id');
    }

    public function membershipAcademy()
    {
        return $this->hasOne('membership\MembershipAcademy', 'academy_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function($academy)
        {
            $academy->updated_by = Auth::id();
			$academy->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($academy)
        {
            $academy->created_by = Auth::id();
			$academy->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($academy)
        {
            //
        });

        static::deleting(function($academy)
        {

		});
    }
}
