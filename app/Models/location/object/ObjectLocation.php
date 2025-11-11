<?php

namespace location\object;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class ObjectLocation extends Model
{
    protected $table = 'rti_object_location';
    protected $primaryKey = 'id';

    public function aimagCity()
    {
        return $this->belongsTo('location\unit\AimagCity', 'address_aimag_city');
    }

    public function soumDistrict()
    {
        return $this->belongsTo('location\unit\SoumDistrict', 'address_soum_district');
    }

    public function bagKhoroo()
    {
        return $this->belongsTo('location\unit\BagKhoroo', 'address_bag_khoroo');
    }

    public function objectType()
    {
        return $this->belongsTo('location\reference\ObjectType', 'object_type');
    }

    public function entrances()
    {
        return $this->hasMany('location\object\Entrance', 'object_location_id');
    }

	public static function boot()
    {
        parent::boot();    

        static::updating(function($objectLocation)
        {
            $objectLocation->updated_by = Auth::id();
			$objectLocation->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($objectLocation)
        {
            $objectLocation->created_by = Auth::id();
			$objectLocation->created_at = Carbon\Carbon::now()->toDateTimeString();
        });
    }
}
