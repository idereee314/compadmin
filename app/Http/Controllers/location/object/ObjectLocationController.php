<?php

namespace location\object;

// Repositories
use location\reference\ObjectTypeRepository as ObjectType;
use location\reference\EntryTypeRepository as EntryType;

use location\unit\AimagCityRepository as AimagCity;
use location\object\ObjectLocationRepository as ObjectLocation;
use location\object\EntranceRepository as Entrance;

// Models
use location\object as ObjectLocationModel;
use location\unit\AmaigCity;

use Illuminate\Http\Request;
use \Response as Response;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use Session;
use Illuminate\Http\FileHelper;

class ObjectLocationController extends Controller
{
    public function __construct(ObjectLocation $objectLocation, AimagCity $aimagCity, ObjectType $objectType, EntryType $entryType, Entrance $entrance) {

        $this->view_path = "location.object";
        $this->objectLocation = $objectLocation;
        $this->aimagCity = $aimagCity;
        $this->objectType = $objectType;
        $this->entryType = $entryType;
        $this->entrance = $entrance;
    }

    public function editObjectLocation()
    {
        $aimagCity = $this->aimagCity->orderByCode();

        $data['view_path'] = $this->view_path;
        $data['aimagCities'] = $aimagCity;
        
        return View::make($this->view_path.'.index', $data);
    }

    public function editObjectLocationInfo()
    {
        $input = Input::all();

        $objectLocation = $this->objectLocation->find($input['object_id']);
        $objectTypes = $this->objectType->byParent();
        $entryType = $this->entryType->byParent();

        $data['view_path'] = $this->view_path;
        $data['objectTypes'] = $objectTypes;
        $data['objectLocation'] = $objectLocation;
        $data['objectId'] = $input['object_id'];
        $data['entryType'] = $entryType;
        

        return View::make($this->view_path.'.object_edit_info', $data);
    }

    public function updateObjectLocationInfo()
    {
        $input = Input::all();

        try
        {
            $objectLocation = $this->objectLocation->update($input);

            foreach(@$input['entry_name'] as $key => $name)
            {
                $uniqArr['object_location_id'] = $objectLocation->id;
                $uniqArr['entry_type'] = @$input['entry_type'][$key];
                $uniqArr['name'] = $name;

                $inputArr['object_location_id'] = $objectLocation->id;
                $inputArr['entry_type'] = @$input['entry_type'][$key];
                $inputArr['name'] = $name;
    
                $objectLocation->entrances()->updateOrCreate($uniqArr, $inputArr);
            }

            $status = true;
            $msg = trans('messages.success_save');
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            $status = false;
            $msg = $e->getMessage();
        }

        $returnResponse = array("status" => $status, 'msg' => @$msg);
        return Response::json($returnResponse);
    }

    public function getObjectData()
    {
        $input = Input::all();
    
        $objectInfo = $this->objectLocation->getObjectData($input);

        if ($objectInfo) {
            $objectGeoJSON = $this->getLocationGeoJSONConvert($objectInfo->geojson);
            $objectInfo = $objectInfo->load('entrances');
                
            return  [$objectInfo, $objectGeoJSON];
        }
        else
        {
            return null;
        }

    }

    public function getObjectById($id)
    {
        $objectInfo = $this->objectLocation->getObjectById($id);

        if ($objectInfo) {
            $objectGeoJSON = $this->getLocationGeoJSONConvert($objectInfo->geojson);
                
            return  [$objectInfo, $objectGeoJSON];
        }
        else
        {
            return null;
        }
    }

    public function getLocationGeoJSONConvert($selectedLocation)
	{
        $returnGeoJson = '{"type":"FeatureCollection","features":[ ';
            
        $returnGeoJson .= '{"type": "Feature", "id": "", "properties":  {"code": "", "name": "", "area" : ""},"geometry": '.$selectedLocation.'},';   

        $returnGeoJson = substr($returnGeoJson, 0, strlen($returnGeoJson) - 1);
        $returnGeoJson .= ']}';
        
        return $returnGeoJson;
    }

    public function getEntranceByObjectId()
    {
        $input = Input::all();
        $entrances = $this->entrance->getEntranceByObjectLocationId(@$input['object_location_id'])->pluck('name', 'entrance_id')->toArray();

        return json_encode($entrances);
    }

    public function getUnitCenterByIdAndType($id, $locationType)
    {
        $selectedLocation = $this->objectLocation->getUnitCenterByIdAndType($id, $locationType);

        if ($selectedLocation != null) {
            $locationGeoJson = $this->getLocationGeoJSONConvert($selectedLocation->geojson);
            
            return $locationGeoJson;
        }

        return null;
    }

    public function getLocationCenterById($id)
    {
        $selectedLocation = $this->objectLocation->getLocationCenterById($id);

        if ($selectedLocation != null) {
            $locationGeoJson = $this->getLocationGeoJSONConvert($selectedLocation->geojson);

            return $locationGeoJson;
        }

        return null;
    }
}
