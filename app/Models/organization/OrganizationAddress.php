<?php

namespace organization;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class OrganizationAddress extends Model
{
    protected $table = 'rti_organization_address';
    protected $primaryKey = 'id';

    public static $rules = array(
        'organization_id' => 'required',
        'location_data' => 'required_without:object_id',
    );

    public function organizationAddressObject()
    {
        return $this->hasOne('location\object\ObjectLocation', 'id', 'object_location_id');
    }

    public function objectLocation()
    {
        return $this->hasOne('location\object\ObjectLocation', 'id', 'object_location_id');
    }

    public function entrance()
    {
        return $this->belongsTo('location\object\Entrance', 'entrance_id');
    }

	public static function boot()
    {
        parent::boot();    

        static::updating(function($organizationAddress)
        {
            $organizationAddress->updated_by = @Auth::user()->user_id;
			$organizationAddress->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($organizationAddress)
        {
            $organizationAddress->created_by = @Auth::user()->user_id;
			$organizationAddress->created_at = Carbon\Carbon::now()->toDateTimeString();
            $organizationAddress->is_active = TRUE;
        });

        static::deleting(function($organizationAddress)
        {
		});

    }
}
