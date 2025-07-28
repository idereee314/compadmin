<?php 

namespace organization;

use organization\OrganizationAddress;
use location\object\ObjectLocation;

use Datatables;
use Session;
use Config;
use DB;

class EloquentOrganizationAddressRepository implements OrganizationAddressRepository
{
    public function find($id)
    {
        return OrganizationAddress::find($id);
    }

    public function all()
    {
        return OrganizationAddress::all();
    }

    public function create($input)
    {
        $organizationAddress = new OrganizationAddress;

        $organizationAddress->organization_id = @$input['organization_id'];
        $organizationAddress->object_location_id = @$input['object_id'];
        $organizationAddress->entrance_id = @$input['entrance_id'];
        $organizationAddress->object_floor_no = @$input['floor_no'];
        $organizationAddress->object_room_no = @$input['room_no'];
        
        if (!empty(@$data['location_data'])) 
        {
            $coords = explode(",", $input['location_data']);
            $organizationAddress->point_geom = $this->getPointfromLatLon($coords[0], $coords[1]);
        }
        
        $organizationAddress->save();
    }

    public function delete($id)
    {
        $organizationAddress = OrganizationAddress::find($id);
        $organizationAddress->delete();
    }

    public function update($id, $data)
    {
        $organizationAddress = OrganizationAddress::find($id);

        $organizationAddress->description = @$data['description'];
        $organizationAddress->object_location_id = @$data['object_id'];
        $organizationAddress->entrance_id = @$data['entrance_id'];
        $organizationAddress->object_room_no = @$data['room_no'];
        $organizationAddress->object_floor_no = @$data['floor_no'];
        
        if (!empty(@$data['location_data'])) 
        {
            $coords = explode(",", $data['location_data']);
            $organizationAddress->point_geom = $this->getPointfromLatLon($coords[0], $coords[1]);
        }
        else
        {
            $organizationAddress->point_geom = null;
        }
        $organizationAddress->save();
    }

    public function updateByOne($data)
    {
        $organizationAddress = OrganizationAddress::find($data['address_id']);


        if(array_key_exists('description', $data)){
            $organizationAddress->description = @$data['description'];
            $value = 'description';
        }

        if(array_key_exists('object_id', $data)){
            $organizationAddress->object_location_id = @$data['object_id'];
            $value = 'object_id';
        }

        if(array_key_exists('room_no', $data)){
            $organizationAddress->object_room_no = @$data['room_no'];
            $value = 'room_no';
        }
        
        if(array_key_exists('floor_no', $data)){
            $organizationAddress->object_floor_no = $data['floor_no'];
            $value = 'floor_no';
        }

        if(array_key_exists('entrance_id', $data)){
            $organizationAddress->entrance_id = $data['entrance_id'];
            $value = 'entrance_id';
        }

        $organizationAddress->save();
        return $organizationAddress;
    }
    /*
    public function getMapCenter($locationId, $locationType)
    {
        switch ($locationType) {
            case 'Aimag':
                $mapCenter = DB::select(" SELECT id, ST_AsGeoJSON(ST_Transform(geometry,4326)) as geometry from rt_listing.rta_aimag_city where id = '".$locationId."' ");

                break;
            case 'Sum':
                $mapCenter = DB::select(" SELECT id, ST_AsGeoJSON(ST_Transform(geometry,4326)) as geometry from rt_listing.rta_soum_district where id = '".$locationId."' ");

                break;
            default:
                $mapCenter = DB::select(" SELECT id, ST_AsGeoJSON(ST_Transform(geometry,4326)) as geometry from rt_listing.rta_bag_khoroo where id = '".$locationId."' ");

                break;
        }

        if(!empty($mapCenter))
        {
            return $mapCenter;
        }

        return null;
    }

    public function getOrganizationAddress($locationId)
    {
        $geojson = DB::select("SELECT id, ST_AsGeoJSON(ST_Transform(geometry, 4326)) as geometry from rt_listing.rti_object_location where id = $locationId");

        if (!empty($geojson)) {
            return $geojson;
        }

        return null;
    }
    */

    public function getOrganizationAddressByPoint($point)
    {
        $getPoint = DB::select("SELECT ST_X(ST_GeomFromEWKT('$point')) as long, ST_Y(ST_GeomFromEWKT('$point')) as lat");

        if (!empty($getPoint)) {
            $coord['lg'] = $getPoint[0]->long;
            $coord['lt'] = $getPoint[0]->lat;

            return $coord;
        }

        return null;
    }

    public function byName($objName)
    {
        //DB::enableQueryLog();
        $parent = "";
        $qry = ObjectLocation::select('object_name as name', 'id')->where('object_name', 'like', '%'.$objName.'%')->orderBy('object_name', 'asc')->get();

		return $qry;
    }

    public function getPointfromLatLon($lat, $lon)
    {
        $latLongPoint = DB::select("select ST_SetSRID(ST_Point($lon, $lat), 4326) as latlong");

        return $latLongPoint = $latLongPoint[0]->latlong;
    }

    public function getBagKhorooByPoint($organizationId)
    {
        $bagKhoroo = DB::select("
        select bag.name, bag.id from rt_listing.rta_bag_khoroo bag
        join rt_listing.rti_organization_address org on ST_Intersects(org.point_geom,  bag.geometry)
        where org.organization_id = $organizationId");

        return $bagKhoroo[0];
    }
}

