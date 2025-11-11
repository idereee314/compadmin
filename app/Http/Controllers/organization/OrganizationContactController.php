<?php

namespace organization;

// Repositories
use organization\OrganizationRepository as Organization;
use organization\OrganizationContactRepository as OrganizationContact;

// Models
use organization\Organization as OrganizationModel;
use organization\OrganizationContact as OrganizationContactModel;
use reference\ContactType;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use Session;
use Illuminate\Http\FileHelper;

class OrganizationContactController extends Controller
{
    public function __construct(Organization $organization, OrganizationContact $organizationContact) {

        $this->view_path = "listing.organization";
        $this->organization = $organization;
        $this->organizationContact = $organizationContact;
    }
    
    public function index()
    {
        //
    }
    
    public function create()
    {
        $input = Input::all();

        $organization = $this->organization->find($input['orgId']);

        $organizationContacts = $organization->contact;

        $contactType = ContactType::all();

        $data['contactType'] = $contactType;

        return View::make($this->view_path.'.organization_contact_add', $data);
    }
    
    public function store()
    {
        $input = Input::all();

        $validator = Validator::make($input, OrganizationContactModel::$rules);

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
                $this->organizationContact->create($input);
                
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
        $organizationContact = $this->organizationContact->find($id);
        $contactType = ContactType::all();

        $data['organizationContact'] = $organizationContact;
        $data['contactTypes'] = $contactType;
        
        return View::make($this->view_path.'.organization_contact_edit', $data);
    }
    
    public function update($id)
    {
        $input = Input::all();

        $validator = Validator::make($input, OrganizationContactModel::$rulesUpdate);
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
                $this->organizationContact->update($id, $input);
                
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
            $this->organizationContact->delete($id);

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
