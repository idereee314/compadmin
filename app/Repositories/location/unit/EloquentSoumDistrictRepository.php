<?php 

namespace location\unit;

use location\unit\SoumDistrict;

use Datatables;
use Session;
use Config;
use \DB;

class EloquentSoumDistrictRepository implements SoumDistrictRepository
{
    public function find($id)
    {
        return SoumDistrict::find($id);
    }

    public function all()
    {
        return SoumDistrict::all();
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

    public function getSoumDistrictByAimagCity($aimagId)
    {
        $soumDistrict = "";
        if(!empty(@$aimagId))
        {
            $qry = SoumDistrict::where('aimag_city_id', $aimagId)->orderBy('code', 'asc');
            $soumDistrict = $qry->get();
        }
        return $soumDistrict;
    }

    public function getSoumDistrictByPoint($point)
    {
        return DB::select("
            select id, code, name from rt_listing.rta_soum_district
            where ST_Contains(geometry, '$point')
        ");
    }

    public function getSoumDistrictByAimagCityPoint($point)
    {
        return DB::select("
            select soum.id, soum.code, soum.name from rt_listing.rta_soum_district soum
            inner join rt_listing.rta_aimag_city aimag on aimag.code = soum.aimag_city_code
            where ST_Contains(aimag.geometry, '$point')
        ");
    }
}