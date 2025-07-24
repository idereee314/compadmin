<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;

use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class EventLocation extends Model
{
    use PostgisTrait;

    protected $table = 'rti_event_location';
    protected $primaryKey = 'id';

    protected $fillable = ['event_id', 'object_location_id', 'description', 'entrance_id', 'point_geom'];

    protected $postgisFields = [
        'point_geom'
    ];

    protected $postgisTypes = [
        'point_geom' => [
            'geomtype' => 'geometry',
            'srid' => 4326
        ]
    ];

    public static $rules = array(
        'event_id' => 'required',
        'point_geom' => 'required_if:object_location_id',
    );

    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }

    public function objectLocation()
    {
        return $this->belongsTo('location\object\ObjectLocation', 'object_location_id');
    }

    public function entrance()
    {
        return $this->belongsTo('location\object\Entrance', 'entrance_id');
    }

    public function objectLocationAddress()
	{
        return $this->belongsTo('location\object\ObjectLocation', 'object_location_id')
            ->join('rta_aimag_city', 'rta_aimag_city.id', '=', 'rti_object_location.address_aimag_city')
            ->join('rta_soum_district', 'rta_soum_district.id', '=', 'rti_object_location.address_soum_district')
            ->join('rta_bag_khoroo', 'rta_bag_khoroo.id', '=', 'rti_object_location.address_bag_khoroo')
            ->whereIn('object_type', @Config::get('smart.location_object_type')['buildings'])
            ->selectRaw("rti_object_location.id, CONCAT(rta_aimag_city.name, ', ', rta_soum_district.name, ', ', rta_bag_khoroo.name, ', ', object_name) as object_address, point_x, point_y");
	}

    public static function boot()
    {
        parent::boot();
    
        // cause a delete of a product to cascade to children so they are also deleted
        static::creating(function($picture)
        {
            $picture->created_at = Carbon\Carbon::now()->toDateTimeString();
            $picture->created_by = Auth::user()->user_id;
        });

        static::updating(function($picture)
        {
            $picture->updated_at = Carbon\Carbon::now()->toDateTimeString();
            $picture->updated_by = Auth::user()->user_id;
        });
    }
}

