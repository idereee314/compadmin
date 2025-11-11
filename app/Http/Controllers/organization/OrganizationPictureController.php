<?php

namespace organization;

// Repositories
use organization\OrganizationPictureRepository as OrganizationPicture;
use organization\OrganizationRepository as Organization;

// Models
use organization\OrganizationPicture as OrganizationPictureModel;
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

class OrganizationPictureController extends Controller
{
    public function __construct(OrganizationPicture $organizationPicture, Organization $organization) {

        $this->view_path = "listing.organization";
        $this->organizationPicture = $organizationPicture;
        $this->organization = $organization;
    }
    
    public function index()
    {
        //
    }
    
    public function create()
    {
        $input = Input::all();

        $organization = $this->organization->find($input['orgId']);

        $insertedPictureType = @$organization->picture;

        $insertedPictureTypeArray = [];

        foreach ($insertedPictureType as $value) {
            if ($value->pictureType->id != @Config::get('smart.org_image_type')['gallery']) {
                $insertedPictureTypeArray[] = $value->pictureType->id;
            }
        }

        $data['pictureType'] = PictureType::where('object_type', Config::get('smart.object_types')[0])->get();
        $data['organizationPictures'] = @$insertedPictureTypeArray;
        $data['pictureOrgId'] = @$input['orgId'];

        return View::make($this->view_path.'.organization_picture_add', $data);
    }
    
    public function store()
    {
        $input = Input::all();

        $validator = Validator::make($input, OrganizationPictureModel::$rules);

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

                $imageName = 'organization_' . date('YmdHis') . '_' . uniqid() . '.jpg';
                //$imagePath = @Config::get('smart.upload_image_dir')['routyweb'].$pictureType->dir_url."/".$imageName;
                $imagePathS3 = $pictureType->dir_url."/".$imageName;
                

                list($baseType, $image) = explode(';', $orginalData);
                list(, $image) = explode(',', $image);
                $imageOrginal = base64_decode($image);

                try 
                {
                    \Storage::disk('s3')->put($imagePathS3, $imageOrginal, 'public');
                    
                    //ImageHelper::cropAndUpload($imagePath, 1920, 1080, $orginalData);
                    foreach(@Config::get('smart.organization_image_size')[@$pictureType->code] as $key => $type)
                    {
                        try 
                        {
                            /*$imagePath = @Config::get('smart.upload_image_dir')['routyweb'].$pictureType->dir_url;
                            if(@$key)
                            {
                                $imagePath .= "/".$key;                                        
                            }
                            $imagePath .= "/".$imageName;
                            
                            ImageHelper::cropAndUpload($imagePath, $type[0], $type[1], $imageData);*/

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
                            /*
                            list($baseType, $image) = explode(';', $imageData);
                            list(, $image) = explode(',', $image);
                            */

                            \Storage::disk('s3')->put($imagePath, (string)$imageCropped, 'public');
                        }
                        catch(\Exception $e)
                        {
                            $validator->errors()->add('', $e->getMessage());
                        }                                 
                    }

                    $input['url'] = $imageName;
                    $this->organizationPicture->create($input);
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
    
    public function destroy($id)
    {
        try {
            $this->organizationPicture->delete($id);

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
