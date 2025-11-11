<?php

namespace organization;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class OrganizationBanner extends Model
{
    protected $table = 'rti_organization_banner';
    protected $primaryKey = 'id';

    public static $rules = array(
        'organization_id' => 'required',
        'name' => 'required',
        'begin_date' => 'required',
        'end_date' => 'required',
    );

    public static $updateRules = array(
        'name' => 'required',
        'begin_date' => 'required',
        'end_date' => 'required',
    );

	public static function boot()
    {
        parent::boot();    

        static::updating(function($banner)
        {
            $banner->updated_by = Auth::id();
			$banner->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($banner)
        {
            $banner->created_by = Auth::id();
			$banner->created_at = Carbon\Carbon::now()->toDateTimeString();
        });
    }
}
