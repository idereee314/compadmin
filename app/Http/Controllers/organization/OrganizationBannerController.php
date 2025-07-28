<?php

namespace organization;

// Repositories
use organization\OrganizationBannerRepository as OrganizationBanner;

// Models
use organization\OrganizationBanner as OrganizationBannerModel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use Session;
use Illuminate\Http\FileHelper;

class OrganizationBannerController extends Controller
{   
    public function __construct(OrganizationBanner $orgBanner) {

        $this->view_path = "listing.organization";
        $this->orgBanner = $orgBanner;
    }
    
    public function index()
    {
    }
    
    public function create()
    {
        return View::make($this->view_path.'.organization_banner_add');
    }
    
    public function store(Request $request)
    {
        $input = Input::all();

        $validator = Validator::make($input, OrganizationBannerModel::$rules);

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
                $this->orgBanner->create($input);
                
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
        $banner = $this->orgBanner->find($id);

        $data['banner'] = $banner;
        
        return View::make($this->view_path.'.organization_banner_edit', $data);
    }
    
    public function update($id)
    {
        $input = Input::all();

        $validator = Validator::make($input, OrganizationBannerModel::$updateRules);

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
                $this->orgBanner->update($id, $input);
                
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
            $this->orgBanner->delete($id);

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
}
