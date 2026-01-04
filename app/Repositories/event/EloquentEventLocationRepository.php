<?php 

namespace event;

use event\EventLocation;
use location\unit\BagKhoroo;

use Datatables;
use Session;
use Config;
use \DB;
use Illuminate\Support\Facades\Storage;
use Auth;

class EloquentEventLocationRepository implements EventLocationRepository
{
    public function find($id)
    {
        return EventLocation::find($id);
    }

    public function all()
    {
        return EventLocation::all();
    }

    public function create($input)
    {
        $eventLocation = new EventLocation;
        $eventLocation->object_location_id = @$input['object_location_id'];
        $eventLocation->point_geom = $input['point_geom'];
        $eventLocation->entrance_id = @$input['entrance_id'];

        $eventLocation->save();
    }

    public function delete($id)
    {
        $eventLocation = EventLocation::find($id);
        $eventLocation->delete();
    }

    public function getDatatableList($searchData)
    {
        $location = EventLocation::selectRaw("rti_event_location.id, rti_event_location.object_location_id, CONCAT(rta_aimag_city.name, ', ', rta_soum_district.name, ', ', rta_bag_khoroo.name) as point_address, ST_X(rti_event_location.point_geom) as coord_x, ST_Y(rti_event_location.point_geom) as coord_y")
            ->leftJoin('rta_bag_khoroo', function($join){
                $join->whereRaw("CASE WHEN object_location_id is null THEN st_intersects(rti_event_location.point_geom, coalesce(rta_bag_khoroo.geometry, 'GEOMETRYCOLLECTION EMPTY')) END");
                //$join->whereRaw('ST_contains(rta_bag_khoroo.geometry, rti_event_location.point_geom)');
            })
            ->leftJoin('rta_soum_district', 'rta_bag_khoroo.soum_district_id','=', 'rta_soum_district.id')
            ->leftJoin('rta_aimag_city', 'rta_soum_district.aimag_city_id','=', 'rta_aimag_city.id')
            //->whereRaw('(CASE WHEN object_location_id is null THEN rta_bag_khoroo.id is not null ELSE 1=1 END)')
            ->where('event_id', $searchData->get('event_id'));
    
        $data = Datatables::of($location)
        ->setRowId(function ($location) {
            return @$location->object_location_id;
        })
        ->addColumn('address', function($location){
            if(!empty(@$location->objectLocationAddress))
            {
                $address = $location->objectLocationAddress->object_address;
            }
            else 
            {
                $address = @$location->point_address;
            }
            return @$address;
        })
        ->addColumn('action', function ($location) 
        {
            $actionHtml = '<a href="javascript:;" class="btn btn-circle btn-danger location-delete" data-eventlocationid='.$location->id.' data-toggle="tooltip" data-placement="top" data-original-title="'.trans('display.general_delete').'"><i class="fa fa-times"></i></a>';
            return $actionHtml;
        })->rawColumns(['action', 'address'])->make(true);

        return $data;
    }
}