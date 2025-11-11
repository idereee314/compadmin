<?php

namespace location\configuration;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class Configuration extends Model
{
    protected $table = 'configuration';
    protected $primaryKey = 'id';

    public static $rules = array(
        'tag_id' => 'required|unique:configuration',
        'tag_key' => 'required',
        'tag_value' => 'required',
        "priority"  => "required",
        "maxspeed"  => "required",
        "maxspeed_forward"  => "required",
        "maxspeed_backward"  => "required",
        "maxspeed_backward"  => "required",
    );

    public static $rulesUpdate = array(
        'tag_key' => 'required',
        'tag_value' => 'required',
        "priority"  => "required",
        "maxspeed"  => "required",
        "maxspeed_forward"  => "required",
        "maxspeed_backward"  => "required",
        "maxspeed_backward"  => "required",
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

        static::deleting(function($configuration)
        {
            $configuration->roadObjectType()->detach();
		});
    }
}
