<?php

namespace location\reference;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class RoadObjectType extends Model
{
    protected $table = 'rtc_road_object_type';
    protected $primaryKey = 'id';

    public static $rules = array(
        'code' => 'required|unique:rtc_road_object_type',
        'description' => 'required',
    );

    public static $rulesUpdate = array(
        'description' => 'required',
    );


    public function roadObjectType()
    {       
        return $this->belongsToMany('location\reference\RoadObjectType', 'rt_osm_data.rtm_osmconf_roadobject', 'osmconf_id', 'road_object_type_id')->orderBy('road_object_type_id', 'asc');
    }

	public static function boot()
    {
        parent::boot();    

        static::updating(function($configuration)
        {
            $configuration->updated_by = Auth::id();
			$configuration->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($configuration)
        {
            $configuration->created_by = Auth::id();
			$configuration->created_at = Carbon\Carbon::now()->toDateTimeString();
        });
    }
}
