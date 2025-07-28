<?php

namespace reference;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class OrganizationStatus extends Model
{
    protected $table = 'rtc_organization_status';
    protected $primaryKey = 'id';
    public $timestamps = false;

	public static function rules($id){
		return array(
            'code' => 'required|unique:rtc_organization_status,code,'.$id,
            "name"  => "required",
		);
	}

	public static function boot()
    {
        parent::boot();    

        static::updating(function($organizationStatus)
        {
            $organizationStatus->updated_by = Auth::id();
			$organizationStatus->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($organizationStatus)
        {
            $organizationStatus->created_by = Auth::id();
			$organizationStatus->created_at = Carbon\Carbon::now()->toDateTimeString();
        });
    }
}
