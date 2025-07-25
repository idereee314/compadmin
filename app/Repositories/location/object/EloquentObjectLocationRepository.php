<?php 

namespace location\object;

use location\object\ObjectLocation;
use location\unit\AimagCity;
use location\unit\SoumDistrict;
use location\unit\BagKhoroo;

use Datatables;
use Session;
use Config;
use \DB;

class EloquentObjectLocationRepository implements ObjectLocationRepository
{
    public function find($id)
    {
        return ObjectLocation::find($id);
    }

    public function all()
    {
        return ObjectLocation::all();
    }

    public function getObjectData($input)
    {
        $lat = $input['latitude'];
        $lon = $input['longitude'];

        $latLongPoint = DB::select("select ST_SetSRID(ST_Point($lon, $lat), 4326) as latlong");

        $latLongPoint = $latLongPoint[0]->latlong;

        $objectData = ObjectLocation::selectRaw("*, st_asgeojson(geometry) as geojson")
            ->whereRaw("ST_Within('".$latLongPoint."', geometry)")->first();
        //$objectData = DB::select("select *, st_asgeojson(geometry) as geojson  from rt_listing.rti_object_location where ST_Within('$latLongPoint', geometry)");
        
        return $objectData;
    }

    public function getObjectById($id)
    {
        $objectLocation = "";
        if(@$id)
        {
            $qry = ObjectLocation::selectRaw("*, st_asgeojson(geometry) as geojson")->where('id', $id);
            $objectLocation = $qry->first();
        }
        
        return $objectLocation;
    }

    public function update($input)
    {
        $objectLocation = $this->find($input["object_id"]);

        $objectLocation->object_type = $input["object_type"];
        $objectLocation->object_name = $input["object_name"];
        $objectLocation->object_floor = $input["object_floor"];
        $objectLocation->status = $input["object_status"];
        
        $objectLocation->save();
        return $objectLocation;
    }

    public function getUnitCenterByIdAndType($locationId, $locationType)
    {
        $unit = "";
        switch ($locationType) {
            case 'Aimag':
                $qry = AimagCity::selectRaw("*, ST_AsGeoJSON(geometry) as geojson")->where('id', $locationId);
                break;

            case 'Sum':
                $qry = SoumDistrict::selectRaw("*, ST_AsGeoJSON(geometry) as geojson")->where('id', $locationId);
                break;

            default:
                $qry = BagKhoroo::selectRaw("*, ST_AsGeoJSON(geometry) as geojson")->where('id', $locationId);

                break;
        }
        
        $unit = $qry->first();
        return $unit;
    }

    public function getLocationCenterById($locationId)
    {
        $objectLocation = "";
        if(!empty($locationId))
        {
            $qry = ObjectLocation::selectRaw("*, ST_AsGeoJSON(geometry) as geojson")->where('id', $locationId);
            $objectLocation = $qry->first();
        }

        return $objectLocation;
    }
}