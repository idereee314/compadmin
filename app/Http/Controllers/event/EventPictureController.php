<?php

namespace event;

// Repositories
use event\EventPictureRepository as EventPicture;
use event\EventRepository as Event;

// Models
use event\EventPicture as EventPictureModel;
use reference\PictureType;

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

class EventPictureController extends Controller
{
    public function __construct(EventPicture $eventPicture, Event $event) {

        $this->view_path = "listing.event";
        $this->eventPicture = $eventPicture;
        $this->event = $event;
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
        $validator = Validator::make($input, EventPictureModel::$rules);

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
            $pictureType = PictureType::find(@$input['picture_type']);
            if(@$pictureType)
            {
                $imageData = $input['croppedData'];
                $orginalData = $input['orginalData'];
                
                $imageName = 'event_' . date('YmdHis') . '_' . uniqid() . '.jpg';
                $imagePathS3 = $pictureType->dir_url."/".$imageName;

                $orgImage = Image::make($orginalData);
                $imageOrginal = $orgImage->encode('jpg');

                try 
                {
                    \Storage::disk('s3')->put($imagePathS3, (string)$imageOrginal, 'public');

                    foreach(@Config::get('smart.event_image_size')[@$pictureType->code] as $key => $type)
                    {
                        try 
                        {
                            $imagePath = $pictureType->dir_url;
                            if(@$key)
                            {
                                $imagePath .= "/".$key;                                        
                            }
                            $imagePath .= "/".$imageName;
                            
                            $image = Image::make($imageData);
                            if(@$image->width() >= @$type[0])
                            {
                                $image = $image->fit($type[0], $type[1]);
                            }

                            $imageCropped = $image->encode('jpg');

                            \Storage::disk('s3')->put($imagePath, (string)$imageCropped, 'public');
                        }
                        catch(\Exception $e)
                        {
                            $validator->errors()->add('', $e->getMessage());
                        }                                 
                    }

                    $input['url'] = $imageName;
                    $this->eventPicture->create($input);
                }
                catch(\Exception $e)
                {
                    $validator->errors()->add('', $e->getMessage());
                }
            }
            else 
            {
                $response = array(
                    'status' => 'error',
                    'msg' => trans('messages.error_no_image_type')
                );
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
    
    public function edit($id)
    {
        $eventPicture = $this->eventPicture->find($id);
        
        if (file_exists(Config::get('smart.upload_image_dir')['routyweb'].'mobile'.$eventPicture->pictureType->dir_url.$eventPicture->url)) 
        {
            $data['imagePath'] = Config::get('smart.image_url')['web'].'mobile'.$eventPicture->pictureType->dir_url.$eventPicture->url;
        }

        $data['pictureType'] = PictureType::where('object_type', Config::get('smart.object_types')[1])->get();
        $data['eventPicture'] = $eventPicture;
        
        return View::make($this->view_path.'.event_picture_edit', $data);
    }
    
    public function update($id)
    {
        $input = Input::all();

        $validator = Validator::make($input, EventPictureModel::$updateRules);
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
                $this->eventPicture->update($id, $input);
                
                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_save')
                );

            } 
            catch (\Illuminate\Database\QueryException $e)
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
            $this->eventPicture->delete($id);

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
}
