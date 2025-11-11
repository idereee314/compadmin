<?php

namespace organization;

// Repositories
use organization\OrganizationRepository as Organization;
use organization\OrganizationAddressRepository as OrganizationAddress;
use location\unit\AimagCityRepository as AimagCity;
use location\unit\SoumDistrictRepository as SoumDistrict;
use location\unit\BagKhorooRepository as BagKhoroo;

// Models
use organization\OrganizationAddress as OrganizationAddressModel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use Session;
use DB;
use Illuminate\Http\FileHelper;

class OrganizationAddressController extends Controller
{
    public function __construct(Organization $organization, OrganizationAddress $orgAddress, AimagCity $aimagCity, SoumDistrict $soumDistrict, BagKhoroo $bagKhoroo) {

        $this->view_path = "listing.organization";
        $this->organization = $organization;
        $this->orgAddress = $orgAddress;
        $this->aimagCity = $aimagCity;
        $this->soumDistrict = $soumDistrict;
        $this->bagKhoroo = $bagKhoroo;
    }
    
    public function index()
    {
        //
    }
    
    public function create()
    {
        $input = Input::all();
        $organization = $this->organization->find($input['orgId']);
        $aimagCity = $this->aimagCity->orderByCode();

        $data['organization'] = $organization;
        $data['aimagCities'] = $aimagCity;

        return View::make($this->view_path.'.organization_address_add', $data);
    }
    
    public function store()
    {
        $input = Input::all();

        $validator = Validator::make($input, OrganizationAddressModel::$rules);

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
            try 
            {
                $this->orgAddress->create($input);
                
                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_save')
                );
            }
            catch(\Illuminate\Database\QueryException $e)
            {
                $response = array(
                    'status' => 'error',
                    'msg' => trans('messages.error_save'),
                    'errors' => $e->getMessage()
                );
            }
        }

        $data['response'] = $response;
        return View::make('core.alert.messages', $data);
    }
    
    public function show($id)
    {
        //
    }
    
    public function edit($id)
    {
        $organizationAddress = $this->orgAddress->find($id);
        $aimagCity = $this->aimagCity->orderByCode();

        if($organizationAddress->object_location_id)
        {
            $soumDistrict = $this->soumDistrict->getSoumDistrictByAimagCity(@$organizationAddress->organizationAddressObject->aimagCity->id);
            $bagKhoroo = $this->bagKhoroo->getBagKhorooBySoumDistrict(@$organizationAddress->organizationAddressObject->soumDistrict->id);

            $aimagCitySelected = @$organizationAddress->organizationAddressObject->aimagCity->id;
            $soumDistrictSelected = @$organizationAddress->organizationAddressObject->soumDistrict->id;
            $bagKhorooSelected = @$organizationAddress->organizationAddressObject->bagKhoroo->id;

            $data['soumDistricts'] = $soumDistrict;
            $data['bagKhoroos'] = $bagKhoroo;
            $data['aimagCitySelected'] = $aimagCitySelected;
            $data['soumDistrictSelected'] = $soumDistrictSelected;
            $data['bagKhorooSelected'] = $bagKhorooSelected;
        }
        else
        {
            $point = $organizationAddress->point_geom;

            $aimagCitySelected = $this->aimagCity->getAimagCityByPoint($point);
            $soumDistrictSelected = $this->soumDistrict->getSoumDistrictByPoint($point);
            $bagKhorooSelected = $this->bagKhoroo->getBagKhorooByPoint(@$point);

            $soumDistrict = $this->soumDistrict->getSoumDistrictByAimagCity(@$aimagCitySelected[0]->id);
            $bagKhoroo = $this->bagKhoroo->getBagKhorooBySoumDistrict(@$soumDistrictSelected[0]->id);

            $data['soumDistricts'] = $soumDistrict;
            $data['bagKhoroos'] = $bagKhoroo;
            $data['aimagCitySelected'] = $aimagCitySelected[0]->id;
            $data['soumDistrictSelected'] = $soumDistrictSelected[0]->id;
            $data['bagKhorooSelected'] = $bagKhorooSelected[0]->id;
        }
        
        $data['organizationAddress'] = $organizationAddress;
        $data['aimagCities'] = $aimagCity;

        return View::make($this->view_path.'.organization_address_edit', $data);
    }
    
    public function update($id)
    {
        $input = Input::all();
        $validator = Validator::make($input, OrganizationAddressModel::$rules);

        // process the save
        if ($validator->fails())
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()
            );
        }
        else {
            try 
            {
                $this->orgAddress->update($id, $input);
                
                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_save')
                );
            }
            catch(\Illuminate\Database\QueryException $e)
            {
                $response = array(
                    'status' => 'error',
                    'msg' => trans('messages.error_save'),
                    'errors' => $e->getMessage()
                );
            }
            
        }

        $data['response'] = $response;
        return View::make('core.alert.messages', $data);
    }
    
    public function updateByOne()
    {
        $input = Input::all();

        try 
        {
            $address = $this->orgAddress->updateByOne($input);            
    
            if(array_key_exists('object_id', $input))
            {
                $result = $address->organizationAddressObject->object_name;
            }
            else if(array_key_exists('entrance_id', $input))
            {
                $result = $address->entrance->name;
            }
            else 
            {
                $key = key($input);
                return $key;
            }
            
            return $result;
        
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            $result = trans('messages.error_save');
        }
        
        return $result;
    }
    
    public function destroy($id)
    {
        try {
            $this->orgAddress->delete($id);

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

    public function getSoumDistrictByAimagCity()
    {
        $input = Input::all();

        $aimagId = $input['aimagId'];

        $soumDistricts = $this->soumDistrict->getSoumDistrictByAimagCity($aimagId);

        $data['soumDistricts'] = $soumDistricts;

        return View::make($this->view_path.'.soum_district_select', $data);
    }

    public function getBagKhorooBySoumDistrict()
    {
        $input = Input::all();

        $soumId = $input['soumId'];

        $bagKhoroos = $this->bagKhoroo->getBagKhorooBySoumDistrict($soumId);

        $data['bagKhoroos'] = $bagKhoroos;

        return View::make($this->view_path.'.bag_khoroo_select', $data);
    }
    /*
    public function getLocationCenterPoint($id, $locationType)
    {
        $selectedLocation = $this->orgAddress->getMapCenter($id, $locationType);

        if ($selectedLocation != null) {
            $locationGeoJson = $this->getLocationGeoJSONConvert($selectedLocation);
            
            return $locationGeoJson;
        }

        return null;
    }
    
    public function getLocationGeoJSONConvert($selectedLocation)
	{
        $returnGeoJson = '{"type":"FeatureCollection","features":[ ';
            
        foreach ($selectedLocation as $location)
        {
            $returnGeoJson .= '{"type": "Feature", "id": "'.$location->id.'", "properties":  {"code": "", "name": "", "area" : ""},"geometry": '.$location->geometry.'},';   
        }

        $returnGeoJson = substr($returnGeoJson, 0, strlen($returnGeoJson) - 1);
        $returnGeoJson .= ']}';
        
        return $returnGeoJson;
    }

    public function getOrganizationAddress($id)
    {
        $selectedLocation = $this->orgAddress->getOrganizationAddress($id);

        if ($selectedLocation != null) {
            $locationGeoJson = $this->getOrganizationGeoJSONConvert($selectedLocation);

            return $locationGeoJson;
        }

        return null;
    }

    public function getOrganizationGeoJSONConvert($selectedLocation)
	{
        $returnGeoJson = '{"type":"FeatureCollection","features":[ ';
            
        foreach ($selectedLocation as $location)
        {
            $returnGeoJson .= '{"type": "Feature", "id": "'.@$location->id.'", "properties":  {"code": "", "name": "", "area" : ""},"geometry": '.@$location->geometry.'},';   
        }

        $returnGeoJson = substr($returnGeoJson, 0, strlen($returnGeoJson) - 1);
        $returnGeoJson .= ']}';
        
        return $returnGeoJson;
    }
*/
    public function getOrganizationAddressByPoint($point)
    {
        $selectedLocation = $this->orgAddress->getOrganizationAddressByPoint($point);

        if ($selectedLocation != null) {
            return $selectedLocation;
        }

        return null;
    }

    public function objectByName()
    {
        $input = Input::all();

        $objects = $this->orgAddress->byName($input['objectName']);

        return json_encode($objects);
    }
}
