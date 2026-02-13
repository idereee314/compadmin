<?php

namespace event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Input;
use Validator;

//Repositories
use event\EventUserRepository as EventUser;
use user\CompadRoleRepository as CompadRole;
use user\CompadUserRepository as CompadUser;
use event\EventRepository as Event;
use sport\SportRepository as Sport;
use location\unit\AimagCityRepository as AimagCity;
use reference\PictureTypeRepository as PictureType;
use reference\CategoryRepository as Category;

//Models
use event\Event as EventModel;
use event\EventUser as EventUserModel;

use \Auth as Auth;
use Config;
use \HTML;
use Image;

class EventController extends Controller
{
    public $restful = true;

    public function __construct(Event $event, CompadUser $compadUser, Sport $sport, AimagCity $aimagCity, PictureType $pictureType, Category $category)
    {
        $this->view_path = 'event.listing';
        $this->compadUser = $compadUser;
        $this->event = $event;
        $this->sport = $sport;
        $this->aimagCity = $aimagCity;
        $this->pictureType = $pictureType;
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $event = $this->event->all();

        $data['events'] = $event;
        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $input = Input::all();
        $sports = $this->sport->all();
        $status = @Config::get('smart.event_status');
        $pictureType = $this->pictureType->all()->where('object_type', Config::get('smart.object_types')[1])->first();
        $eventOrganizationRoles = @Config::get('enums.event_organization_role');
        $aimagCity = $this->aimagCity->all();
        $categories = $this->category->byParent(@Config::get('smart.category_type')['event'], false);

        $data['categories'] = $categories;
        $data['statuses'] = $status;
        $data['pictureType'] = $pictureType;
        $data['roles'] = $eventOrganizationRoles;
        $data['aimagCity'] = $aimagCity;
        $data['sports'] = $sports;
        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $input = Input::all();
        
        $validator = Validator::make($input, EventModel::rules(0));
        $dates = array();
        
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
                $event = $this->event->create($input);

                $event->categories()->sync(@$input['category']);

                if(@$input['event_date'])
                {
                    foreach(@$input['event_date'] as $key => $date)
                    {
                        $arr['start_date'] = $date.' '.$input['start_time'][$key];
                        $arr['end_date'] = $date.' '.$input['end_time'][$key];
                        array_push($dates, $arr);
                    }
                    $event->datetimes()->createMany($dates);
                }

                foreach(@$input['roles'] as $key => $role)
                {
                    $orgArr = explode(",", $input['organizations'][$key]);
                    foreach($orgArr as $org)
                    {
                        if(!empty($org))
                        {
                            $uniqOrgArr['event_id'] = $event->id;
                            $uniqOrgArr['organization_id'] = $org;
                            $uniqOrgArr['role'] = $role;
                                
                            $event->organizations()->updateOrCreate($uniqOrgArr, $uniqOrgArr);
                        }
                    }
                }

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
                                
                            $event->locations()->updateOrCreate($uniqLocationArr, $uniqLocationArr);
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
                            
                        $event->locations()->create($locationArr);
                    }
                }
                
                $pictureType = $this->pictureType->find(@$input['picture_type']);

                if ($pictureType && !empty($input['croppedData']) && !empty($input['orginalData'])) {
                
                    $imageData   = $input['croppedData'];
                    $orginalData = $input['orginalData'];
                
                    $imageName   = 'event_' . date('YmdHis') . '_' . uniqid() . '.jpg';
                    $imagePathS3 = $pictureType->dir_url . "/" . $imageName;
                
                    // 1) CONFIG шалгах
                    $allSizes = config('smart.event_image_size');
                    if (!is_array($allSizes) || !array_key_exists($pictureType->code, $allSizes)) {
                        $validator->errors()->add(
                            'picture',
                            "smart.event_image_size[{$pictureType->code}] тохиргоо олдсонгүй."
                        );
                        throw new \RuntimeException("event_image_size config алга байна");
                    }
                
                    $sizes = $allSizes[$pictureType->code];
                    if (!is_array($sizes) || empty($sizes)) {
                        $validator->errors()->add(
                            'picture',
                            "smart.event_image_size[{$pictureType->code}] хоосон эсвэл буруу байна."
                        );
                        throw new \RuntimeException("event_image_size хоосон байна");
                    }
                
                    // 2) original base64-аас data-г салгаж decode хийх
                    if (strpos($orginalData, 'base64,') !== false) {
                        list($baseType, $image) = explode(';', $orginalData, 2);
                        list(, $image) = explode(',', $image, 2);
                    } else {
                        $image = $orginalData;
                    }
                    $imageOrginal = base64_decode($image);
                
                    try {
                        // эх хувилбарыг S3 руу
                        \Storage::disk('s3')->put($imagePathS3, (string) $imageOrginal, 'public');
                    
                        // 3) тохиргооны бүх хэмжээгээр crop хийх
                        foreach ($sizes as $key => $type) {
                            // type нь заавал [w, h] массив байх ёстой
                            if (!is_array($type) || count($type) < 2) {
                                // буруу config байвал алгасах эсвэл алдаа нэмэх
                                $validator->errors()->add(
                                    'picture',
                                    "Image size config алдаа: {$pictureType->code}.{$key}"
                                );
                                continue;
                            }
                        
                            [$w, $h] = $type;
                        
                            $imagePath = $pictureType->dir_url;
                            if (!empty($key)) {
                                $imagePath .= "/" . $key;
                            }
                            $imagePath .= "/" . $imageName;
                        
                            $image = \Image::make($imageData);
                            if ($image->width() >= $w) {
                                $image = $image->fit($w, $h);
                            }
                        
                            $imageCropped = $image->encode('jpg');
                            \Storage::disk('s3')->put($imagePath, (string) $imageCropped, 'public');
                        }
                    
                        $inputPicture = [
                            'picture_type_id' => $pictureType->id,
                            'url'             => $imageName,
                        ];
                        $event->pictures()->create($inputPicture);
                    
                    } catch (\Exception $e) {
                        $validator->errors()->add('picture', $e->getMessage());
                    }
                
                } else {
                    $validator->errors()->add('picture', trans('messages.no_picture_type'));
                }

            }
            catch(\Illuminate\Database\QueryException $e)
            {
                $validator->errors()->add('', $e->getMessage());
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

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $input = Input::all();
        $event = $this->event->find($id);
        @$sport = $event->eventSport->sport->name;
        
        $data['sport'] = $sport;
        $data['event'] = $event;

        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.description', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $eventUser = $this->eventUser->find($id);

        // $data['eventUser'] = $eventUser;
        // $data['view_path'] = $this->view_path;

        // return view($this->view_path.'.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = Input::all();

        $validator = Validator::make($input, EventModel::rules($id));

        if ($validator->fails())
		{
        	$response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => html_entity_decode(HTML::ul($validator->errors()->all()))
            );
        } else {
			try {

            
				$response = array(
					'status' => 'success',
					'msg' => trans('messages.success_update')
				);
			}
			catch(Exception $e)
			{
				$response = array(
					'status' => 'error',
					'msg' => trans('messages.error_save'),
					'errors' => $e->getMessage()
				);
			}
		}

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->event->delete($id);

            $response = array(
                'status' => 'success',
                'msg' => trans('messages.success_delete')
            );
    
        } catch(\Illuminate\Database\QueryException $e)
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_delete'),
                'errors' => $e
            );
        }

        return $response;
    }

    public function getDatatableList(Request $request)
    {
        return $this->event->getDatatableList($request);
    }

}
