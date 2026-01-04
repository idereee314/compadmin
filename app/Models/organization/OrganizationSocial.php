<?php

namespace organization;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class OrganizationSocial extends Model
{
    protected $table = 'rti_organization_social';
    protected $primaryKey = 'id';

    public static $rules = array(
        'name' => 'required',
        'social_type' => 'required',
        'url' => 'required',
        'organization_id' => 'required',
    );

    public static $rulesUpdate = array(
        'name' => 'required',
        'social_type' => 'required',
        'url' => 'required',
    );

	public static function boot()
    {
        parent::boot();    

        static::updating(function($social)
        {
            $social->updated_by = Auth::id();
			$social->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($social)
        {
            $social->created_by = Auth::id();
			$social->created_at = Carbon\Carbon::now()->toDateTimeString();
        });
    }
}
