<?php

namespace country;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use user\CompadUser as User;
use organization\Organization;

use Auth;
use Carbon;

class Country extends Model
{
    protected $table = 'uq_country';
    
    public static function rules($id) 
    {
		return array(
            'name' => 'required',
            'name_en' => 'required',
		);
	}

    public function member()
    {
        return $this->belongsTo('member/Member', 'country_id');
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function($country)
        {
            $country->updated_by = Auth::id();
			$country->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($country)
        {
            $country->created_by = Auth::id();
			$country->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($country)
        {
            //
        });

        static::deleting(function($country)
        {

		});
    }
}
