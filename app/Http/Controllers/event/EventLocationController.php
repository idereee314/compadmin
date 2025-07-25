<?php

namespace event;

// Repositories
use event\EventLocationRepository as EventLocation;
use event\EventRepository as Event;
use location\object\ObjectLocationRepository as ObjectLocation;
use location\unit\AimagCityRepository as AimagCity;

// Models
use event\EventLocation as EventLocationModel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use Session;
use Image;
use ImageHelper;
use Illuminate\Http\FileHelper;
use Illuminate\Support\Facades\Storage;
use MStaack\LaravelPostgis\Geometries\Point;

class EventLocationController extends Controller
{
    public function __construct(EventLocation $eventLocation, Event $event, ObjectLocation $objectLocation) {

        $this->view_path = "listing.event";
        $this->eventLocation = $eventLocation;
        $this->event = $event;
        $this->objectLocation = $objectLocation;
    }
    
    public function index()
    {
        //
    }
    
    public function create()
    {
        $input = Input::all();
        $event = $this->event->find($input['eventId']);

        $data['pictureType'] = PictureType::where('object_type', Config::get('smart.object_types')[1])->get();
        $data['insertedPictures'] = @$event->pictures()->pluck('id')->toArray();
        $data['eventId'] = @$event->id;

        return View::make($this->view_path.'.event_picture_add', $data);
    }
    
    public function store()
    {
        $input = Input::all();
        $rules = array(
            'event_id' => 'required',
            'location_datas' => 'required_if:object_locations,location_datas',
        );
        $validator = Validator::make($input, $rules);

        // process the save
        if ($validator->fails())
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()
            );
        }
        else 
        {   
            $event = $this->event->find(@$input['event_id']);
            if(@$input['is_all_branch'])
            {
                
            }
            else 
            {
                if(!empty(@$input['object_locations']))
                {
                    $uniqLocationArr = array();
                    $objectLocations = explode(",", $input['object_locations']);
                    foreach($objectLocations as $location)
                    {
                        
                        $uniqLocationArr['event_id'] = $event->id;
                        $uniqLocationArr['object_location_id'] = $location;
                        
                        try 
                        {   
                            $event->locations()->updateOrCreate($uniqLocationArr, $uniqLocationArr);
                        }
                        catch(\Illuminate\Database\QueryException $e)
                        {
                            $validator->errors()->add('', $e->getMessage());
                        }
                    }
                }
            }

            if(@$input['location_datas'])
            {
                $points = $objectLocations = explode(",", $input['location_datas']);
                foreach($points as $point)
                {
                    $coords = explode(';', $point);
                    $locationArr['event_id'] = $event->id;
                    $locationArr['point_geom'] = new Point($coords[0], $coords[1]);
                    
                    try 
                    { 
                        $event->locations()->create($locationArr);
                    }
                    catch(\Illuminate\Database\QueryException $e)
                    {
                        $validator->errors()->add('', $e->getMessage());
                    }
                }
            }        
        }

        if(count($validator->errors()) > 0)
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()
            );
        }
        else
        {
            $response = array(
                'status' => 'success',
                'msg' => trans('messages.success_save')
            ); 
        }

        $data['response'] = $response;
        return View::make('core.alert.messages', $data);
    }
    
    public function show($id)
    {
        //
    }
    
    public function destroy($id)
    {
        try {
            $this->eventLocation->delete($id);

            $response = array(
                'status' => 'success',
                'msg' => trans('messages.success_delete')
            );
        } catch(\Illuminate\Database\QueryException $e)
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_delete')
            );
        }
        

        $data['response'] = $response;
        return View::make('core.alert.messages', $data);
    }

    public function getDatatableList(Request $request)
    {
        return $this->eventLocation->getDatatableList(@$request);
    }
}
