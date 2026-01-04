<?php

namespace organization;

// Repositories
use organization\OrganizationSocialRepository as OrganizationSocial;
use organization\OrganizationRepository as Organization;

// Models
use organization\OrganizationSocial as OrganizationSocialModel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use Session;
use Illuminate\Http\FileHelper;

class OrganizationSocialController extends Controller
{
    public function __construct(OrganizationSocial $organizationSocial, Organization $organization) {

        $this->view_path = "listing.organization";
        $this->organizationSocial = $organizationSocial;
        $this->organization = $organization;
    }
    
    public function index()
    {
        //
    }
    
    public function create()
    {
        return View::make($this->view_path.'.organization_social_add');
    }
    
    public function store()
    {
        $input = Input::all();

        $validator = Validator::make($input, OrganizationSocialModel::$rules);

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
                $this->organizationSocial->create($input);
                
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
        $organizationSocial = $this->organizationSocial->find($id);
        
        $data['organizationSocial'] = $organizationSocial;
        
        return View::make($this->view_path.'.organization_social_edit', $data);
    }
    
    public function update($id)
    {
        $input = Input::all();

        $validator = Validator::make($input, OrganizationSocialModel::$rulesUpdate);
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
                $this->organizationSocial->update($id, $input);
                
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
            $this->organizationSocial->delete($id);

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
