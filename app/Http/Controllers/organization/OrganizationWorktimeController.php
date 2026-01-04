<?php

namespace organization;

// Repositories
use organization\OrganizationWorktimeRepository as OrganizationWorktime;
use organization\OrganizationRepository as Organization;

// Models
use organization\OrganizationWorktime as OrganizationWorktimeModel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use Session;
use Illuminate\Http\FileHelper;

class OrganizationWorktimeController extends Controller
{
    public function __construct(OrganizationWorktime $organizationWorktime, Organization $organization) {

        $this->view_path = "listing.organization";
        $this->organizationWorktime = $organizationWorktime;
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
        $organizationWorkdays = $organization->worktimes()->pluck('work_day')->toArray();

        $workDay = 0;

        for ($i=1; $i < 6; $i++) { 
            if (in_array($i, $organizationWorkdays)) {
                $workDay++;
            }
        }

        if ($workDay == 5) {
            array_push($organizationWorkdays, 0);
        }

        $data['workdays'] = Config::get('listing.workdays');
        $data['org_workdays'] = @$organizationWorkdays;

        return View::make($this->view_path.'.organization_worktime_add', $data);
    }
    
    public function store()
    {
        $input = Input::all();
        $validator = Validator::make($input, OrganizationWorktimeModel::$rules);

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
                $this->organizationWorktime->create($input);
                
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
        $organizationWorktime = $this->organizationWorktime->find($id);
        
        $data['startTime'] = date('H:i', strtotime($organizationWorktime->start_time));
        $data['endTime'] = date('H:i', strtotime($organizationWorktime->end_time));
        $data['organizationWorktime'] = $organizationWorktime;

        return View::make($this->view_path.'.organization_worktime_edit', $data);
    }
    
    public function update($id)
    {
        $input = Input::all();

        $validator = Validator::make($input, OrganizationWorktimeModel::$rulesUpdate);
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
                $organizationWorktime = $this->organizationWorktime->update($id, $input);
                
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
            $this->organizationWorktime->delete($id);

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
