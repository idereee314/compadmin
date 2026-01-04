<?php 

namespace location\unit;

use location\unit\BagKhoroo;

use Datatables;
use Session;
use Config;
use \DB;

class EloquentBagKhorooRepository implements BagKhorooRepository
{
    public function find($id)
    {
        return BagKhoroo::find($id);
    }

    public function all()
    {
        return BagKhoroo::all();
    }

    public function create($input)
    {
      
    }

    public function delete($id)
    {
    }

    public function update($id, $data)
    {
        
    }

    public function getDatatableList($searchData)
    {
      
    }

    public function getObjectData($input)
    {
        $lat = $input['latitude'];
        $lon = $input['longitude'];

        $latLongPoint = DB::select("select ST_SetSRID(ST_Point($lon, $lat), 4326) as latlong");

        $latLongPoint = $latLongPoint[0]->latlong;

        $objectData = DB::select("select *, st_asgeojson(geometry) as geojson  from rt_listing.rti_object_location where ST_Within('$latLongPoint', geometry)");
        
        return $objectData;
    }

    public function getBagKhorooBySoumDistrict($soumId)
    {
        $bagKhoroo = "";
        if(!empty(@$soumId))
        {
            $qry = BagKhoroo::where('soum_district_id', $soumId)->orderByRaw("NULLIF(regexp_replace(name, '\D', '', 'g'), '')::int");
            $bagKhoroo = $qry->get();
        }

        return $bagKhoroo;
    }

    public function getBagKhorooByPoint($point)
    {
        return DB::select("
            select id, code, name from rt_listing.rta_bag_khoroo
            where ST_Contains(geometry, '$point')
        ");
    }

    public function getbagKhorooBySoumDistrictPoint($point)
    {
        return DB::select("
            select id, code, name from rt_listing.rta_bag_khoroo
            where ST_Contains(geometry, '$point')
        ");
    }
}