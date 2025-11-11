<?php

namespace organization;

// Repositories
use reference\OrganizationTypeRepository as OrganizationType;
use reference\CategoryRepository as Category;
use reference\ServiceRepository as Service;
use reference\OrganizationStatusRepository as OrganizationStatus;
use reference\FeaturesRepository as Features;
use reference\PictureTypeRepository as PictureType;
use reference\ContactTypeRepository as ContactType;

use location\unit\BagKhorooRepository as BagKhoroo;

use organization\OrganizationRepository as Organization;
use organization\OrganizationAddressRepository as OrganizationAddress;

use event\EventRepository as Event;

// Models
use organization\Organization as OrganizationModel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use Session;
use Illuminate\Http\FileHelper;
use ImageHelper;
use App\Libraries\Classes\Guzzle\GeoGuzzleHelper;
use Datatables;
use Image;

class OrganizationController extends Controller
{
    public function __construct(Organization $organization, OrganizationAddress $orgAddress, Category $category, OrganizationStatus $organizationStatus, OrganizationType $organizationType, Event $event, Features $features, PictureType $pictureType, ContactType $contactType, BagKhoroo $bagKhoroo, Service $service) {

        $this->view_path = "listing.organization";
        $this->organization = $organization;
        $this->orgAddress = $orgAddress;
        $this->category = $category;
        $this->organizationStatus = $organizationStatus;
        $this->organizationType = $organizationType;
        $this->event = $event;
        $this->features = $features;
        $this->pictureType = $pictureType;
        $this->contactType = $contactType;
        $this->bagKhoroo = $bagKhoroo;
        $this->service = $service;
    }
    
    public function index()
    {                
        $categories = $this->category->byParent(@Config::get('smart.category_type')['organization']);
        $organizationStatus = $this->organizationStatus->all();

        $data['view_path'] = $this->view_path;
        $data['categories'] = $categories;
        $data['organizationStatus'] = $organizationStatus;

        return View::make($this->view_path.'.index', $data);
    }
    
    public function create()
    {
        $category = $this->category->byParent(@Config::get('smart.category_type')['organization']);
        $features = $this->features->all();
        $types = $this->organizationType->all();

        $data['types'] = $types;
        $data['categories'] = $category;
        $data['features'] = $features;

        return View::make($this->view_path.'.add', $data);
    }
    
    public function store()
    {
        $input = Input::all();

        $validator = Validator::make($input, OrganizationModel::$rules);

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
                $this->organization->create($input);
                
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
		$data['tabs'] = collect(@Config::get('listing.organization_tabs'))->sortBy('order')->toArray();;
		$data['org_id'] = $id;
        $data['tab_id'] = 'tab2-1';
        $data['view_path'] = $this->view_path;
        
        return View::make($this->view_path.'.edit', $data);
    }
    
    public function update(Request $request, $id)
    {
        $input = Input::all();

        $validator = Validator::make($input, OrganizationModel::$rules);
            
        // process the save
        if ($validator->fails())
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.information_is_incomplete'),
                'errors' => $validator->errors()
            );
        }
        else {
            try
            {
                $organization = $this->organization->update($id, $input);
                
                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_save')
                );
            } catch (\Illuminate\Database\QueryException $e)
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
    
    public function destroy($id)
    {
        try {
            $this->organization->delete($id);

            $response = array(
                'status' => 'success',
                'msg' => trans('messages.success_delete')
            );
        } catch(\Illuminate\Database\QueryException $e)
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_delete'),
                'errors' => $e->getMessage()
            );
        }
        

        $data['response'] = $response;
        return View::make('core.alert.messages', $data);
    }

    public function getDatatableList(Request $request)
    {
        return $this->organization->getDatatableList(@$request);
    }

	public function includeTab()
    {
		$input = Input::all();
        
        $organization = $this->organization->find(@$input['id']);
        
        if($input['code'] == 'general')
        {
            $category = $this->category->byParent(@Config::get('smart.category_type')['organization']);
            $features = $this->features->all();
            $types = $this->organizationType->all();
            $organizationStatus = $organization->organizationStatusLast;

            $data['types'] = $types;
			$data['features'] = $features;
            $data['categories'] = $category;
            $data['organizationStatus'] = $organizationStatus;
        }
        else if($input['code'] == 'worktimes')
        {
            $worktimes = $organization->worktimes;

			$data['worktimes'] = $worktimes;
		}
        else if($input['code'] == 'address')
        {
            $address = $organization->address;

            if ($address) {
                if($address->organizationAddressObject)
                {
                    $bagKhoroo = @$address->organizationAddressObject->bagKhoroo->name;
                    $soumDistrict = @$address->organizationAddressObject->soumDistrict->name;
                    $aimagCity = @$address->organizationAddressObject->aimagCity->name;

                    $data['bagKhoroo'] = $bagKhoroo;
                    $data['soumDistrict'] = $soumDistrict;
                    $data['aimagCity'] = $aimagCity;
                }
                else
                {
                    $bagKhoroo = @$this->orgAddress->getBagKhorooByPoint($organization->id);
                    $soumDistrict = @$this->bagKhoroo->find($bagKhoroo->id)->soumDistrict;

                    $data['bagKhoroo'] = @$bagKhoroo->name;
                    $data['soumDistrict'] = @$soumDistrict->name;
                    $data['aimagCity'] = @$soumDistrict->aimagCity->name;
                }
            }

            $data['address'] = $address;
		}
        else if($input['code'] == 'banner')
        {
            $banner = $organization->banner;

			$data['banners'] = $banner;
		}
        else if($input['code'] == 'contact')
        {
            $contact = $organization->contact;

			$data['contacts'] = $contact;
		}
        else if($input['code'] == 'pictures')
        {
            $pictures = $organization->picture;

            $data['pictures'] = $pictures;
		}
        else if($input['code'] == 'event')
        {
            $events = $organization->event;

            $data['events'] = $events;
        }
        else if($input['code'] == 'social')
        {
            $socials = $organization->social;

            $data['socials'] = $socials;
        }
        else
        {
            $status = $organization->organizationStatus;

            $data['status'] = $status;
        }
        
        $data['organization'] = $organization;
        $data['tab_id'] = $input['tab_id'];
        $data['view_path'] = $this->view_path;

        return View::make($this->view_path.'.'.$input['name'], $data);
    }
    
    public function addService()
    {
        $input = Input::all();
        $organization = $this->organization->find($input['orgId']);

        $data['organization'] = @$organization;

        return View::make($this->view_path.'.organization_service_add', $data);
    }

    public function attachService($id)
    {
        $input = Input::all();
        $organization = $this->organization->find($id);

        try {
            $services = explode(",", @$input['service_id']);

            $organization->services()->attach($services);
            
            $response = array(
                'status' => 'success',
                'msg' => trans('messages.success_save')
            );
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_delete')
            );
        }
            
        $data['response'] = $response;
        return View::make('core.alert.messages', $data);
    }

    public function detachService($id)
    {
        $input = Input::all();
        $organization = $this->organization->find($id);
        
        try {
            $organization->services()->detach($input['service_id']);
            
            $response = array(
                'status' => 'success',
                'msg' => trans('messages.success_delete')
            );
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_delete')
            );
        }
            
        $data['response'] = $response;
        return View::make('core.alert.messages', $data);
    }

    public function createPicture($orgId)
    {
        $organization = $this->organization->find($orgId);

        $data['pictureType'] = $this->pictureType->all();
        $data['organizationPictures'] = @$organization->picture()->pluck('id')->toArray();

        return View::make($this->view_path.'.organization_picture_add', $data);
    }
    
    public function addEvent()
    {
        $input = Input::all();

        $organization = $this->organization->find($input['orgId']);

        $events = $this->event->all();

        $data['events'] = $events;
        $data['org_events'] = @$organization->event()->pluck('rt_listing.rti_event.id')->toArray();

        return View::make($this->view_path.'.organization_event_add', $data);
    }

    public function attachEvent()
    {
        $input = Input::all();

        $this->organization->attachEvent($input);
        
		$response = array(
			'status' => 'success',
			'msg' => trans('messages.success_save')
		);
		
		$data['response'] = $response;
        return View::make('core.alert.messages', $data);
    }

    public function detachEvent($id)
    {
        $input = Input::all();

        $this->organization->detachEvent($id, $input);
        
		$response = array(
			'status' => 'success',
			'msg' => trans('messages.success_delete')
		);
		
		$data['response'] = $response;
        return View::make('core.alert.messages', $data);
    }

    public function organizationByParent()
    {
        $input = Input::all();

        $organizations = $this->organization->byParent($input['q']);
        return json_encode($organizations);
    }

    public function findOrganizationByName()
    {
        $input = Input::all();
        $organizations = $this->organization->findOrganizationByName($input['q'], @$input['orgIds'],null,true);

        return json_encode($organizations);
    }

    public function copyOrganization($orgId)
    {
        $organization = $this->organization->find($orgId);
        $pictureType = $organization->picture->unique('picture_type_id');
        $contactType = $organization->contact->unique('contact_type_id');

        $data['pictureType'] = $pictureType;
        $data['contactType'] = $contactType;
        $data['organization'] = $organization;

        return View::make($this->view_path.'.organization_copy', $data);
    }

    public function saveCopiedOrganization()
    {
        $input = Input::all();
        $organization = $this->organization->find($input['organization_id']);
        
        try {
            $newOrganization = $this->organization->copyOrganization($input);
            $newOrganization->categories()->attach(@$organization->categories->pluck('id')->toArray());

            if(array_key_exists('worktime', $input))
            {       
                foreach($organization->worktimes as $worktime)
                {
                    $newOrganizationWorkTime = $worktime->replicate();
                    $newOrganization->worktimes()->save($newOrganizationWorkTime);
                }
            }

            if(array_key_exists('picture_types', $input))
            {   
                foreach ($organization->picture->whereIn('picture_type_id', @$input['picture_types']) as $picture)
                {
                    $newOrganizationImage = $picture->replicate();
                    $pictureType = $this->pictureType->find($picture->picture_type_id);
                    
                    try
                    {
                        $fileName = ImageHelper::duplicateImage($picture->url, $pictureType->dir_url, $pictureType->object_type);
                        if(!empty($fileName))
                        {
                            $newOrganizationImage->url = $fileName;
                            $newOrganization->picture()->save($newOrganizationImage);
                        }
                    }
                    catch (\Exception $e)
                    {
                        $response = array(
                            'status' => 'error',
                            'msg' => trans('messages.error_save'),
                            'errors' => $e->getMessage()
                        );
                    }
                }
            }

            if(array_key_exists('contact_types', $input))
            {
                foreach ($organization->contact->whereIn('contact_type_id', @$input['contact_types']) as $contact)
                {
                    $newOrganizationContact = $contact->replicate();
                    $newOrganization->contact()->save($newOrganizationContact);
                }
            }

            if(array_key_exists('organization_service', $input))
            {         
                $newOrganization->services()->attach(@$organization->services->pluck('id')->toArray());
            }
        } catch (\Exception $e)
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $e->getMessage()
            );
        }
    }

    public function addStatus()
    {
        $input = Input::all();

        $organizationStatus = $this->organizationStatus->all();
        $statusSelected = $this->organization->find($input['organization_id'])->organizationStatus->pluck('id')->toArray();

        $data['statusSelected'] = $statusSelected;
        $data['organizationStatus'] = $organizationStatus;

        return View::make($this->view_path.'.organization_add_status', $data);
    }

    public function attachStatus()
    {
        $input = Input::all();
        if ($input['status_id'] == @Config::get('smart.org_status')['verified']) {
            $validator = Validator::make($input, OrganizationModel::$rulesStatus);
                
            // process the save
            if ($validator->fails())
            {
                $response = array(
                    'status' => 'error',
                    'msg' => trans('messages.information_is_incomplete'),
                    'errors' => $validator->errors()
                );
            }
            else {
                try
                {
                    $organization = $this->organization->attachStatus($input);
                    if(@$organization->organizationStatusLast->code == @Config::get('smart.organization_status')['verified'])
                    {
                        $request = @$organization->requests->first();
                        if(@$request)
                        {
                            $request->status = @Config::get('smart.request_status')['approved'];
                            $request->save();

                            foreach($organization->picture as $picture)
                            {
                                $pictureType = $picture->pictureType;
                                if(@$pictureType)
                                {
                                    $orginalData = $picture->image64;

                                    $imageName = 'organization_' . date('YmdHis') . '_' . uniqid() . '.jpg';
                                    $imagePathS3 = $pictureType->dir_url."/".$imageName;

                                    list($baseType, $image) = explode(';', $orginalData);
                                    list(, $image) = explode(',', $image);
                                    $imageOrginal = base64_decode($image);

                                    try 
                                    {
                                        \Storage::disk('s3')->put($imagePathS3, $imageOrginal, 'public');
                                        
                                        foreach(@Config::get('smart.organization_image_size')[@$pictureType->code] as $key => $type)
                                        {
                                            try 
                                            {
                                                $imagePath = $pictureType->dir_url;
                                                if(@$key)
                                                {
                                                    $imagePath .= "/".$key;                                        
                                                }
                                                $imagePath .= "/".$imageName;
                                                
                                                $image = Image::make($orginalData);
                                                if(@$image->width() >= @$type[0])
                                                {
                                                    $image = $image->fit($type[0], $type[1]);
                                                }

                                                $imageCropped = $image->encode('jpg');

                                                \Storage::disk('s3')->put($imagePath, (string)$imageCropped, 'public');
                                            }
                                            catch(\Exception $e)
                                            {
                                                return $e->getMessage();
                                            }                                 
                                        }
                                        $picture->image64 = null;
                                        $picture->url = $imageName;
                                        $picture->save();
                                    }
                                    catch(\Exception $e)
                                    {
                                        return $e->getMessage();
                                    }
                                }
                            }
                        }
                    }

                    $response = array(
                        'status' => 'success',
                        'msg' => trans('messages.success_save')
                    );
                } catch (\Illuminate\Database\QueryException $e)
                {
                    $response = array(
                        'status' => 'error',
                        'msg' => trans('messages.error_save'),
                        'errors' => $e->getMessage()
                    );
                }
            }
        }
        else
        {
            $validator = Validator::make($input, [
                'status_id' => 'required',
                'organization_id' => 'required',
            ]);
                
            // process the save
            if ($validator->fails())
            {
                $response = array(
                    'status' => 'error',
                    'msg' => trans('messages.information_is_incomplete'),
                    'errors' => $validator->errors()
                );
            }
            else {
                try
                {
                    $organization = $this->organization->attachStatus($input);

                    $response = array(
                        'status' => 'success',
                        'msg' => trans('messages.success_save')
                    );
                } catch (\Illuminate\Database\QueryException $e)
                {
                    $response = array(
                        'status' => 'error',
                        'msg' => trans('messages.error_save'),
                        'errors' => $e->getMessage()
                    );
                }
            }
        }
        
        $data['response'] = $response;
        return View::make('core.alert.messages', $data);
    }

    public function getCategoriesByOrgId()
    {
        $input = Input::all();
        $categories = $this->category->getCategoriesByOrgId(@$input['orgId']);

        return json_encode($categories);
    }

    public function getServicesByCategoryId()
    {
        $input = Input::all();
        $services = $this->service->getServicesByCategoryId(@$input['node_id']);

        return json_encode($services);
    }

    public function burtgel()
    {                
        $data['view_path'] = $this->view_path;
        return View::make($this->view_path.'.burtgel', $data);
    }

    public function getOrganizationFromBurtgel(Request $request)
    {
        $body = [];
        $response = GeoGuzzleHelper::callGeoGuzzleHttp('POST', 'http://opendata.burtgel.gov.mn/les/table?start=0&length=10000000000000&search[value]='.@$request->get('text'), $body, null, null, true, true);
        $data = Datatables::of($response['data'])
        //->rawColumns([''])
        ->make(true);

        return $data;
    }

}
