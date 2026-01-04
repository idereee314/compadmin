<?php

namespace location\reference;

// Repositories
use location\reference\RoadObjectTypeRepository as RoadObjectType;

// Models
use location\reference\RoadObjectType as RoadObjectTypeModel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use Session;
use Illuminate\Http\FileHelper;

class RoadObjectTypeController extends Controller
{
    public function __construct(RoadObjectType $roadObjectType) {

        $this->view_path = "location.road.object.type";
        $this->roadObjectType = $roadObjectType;
    }
    
    public function index()
    {
        $data['view_path'] = $this->view_path;

        return View::make($this->view_path.'.index', $data);
    }
    
    public function create()
    {
        return View::make($this->view_path.'.add');
    }
    
    public function store(Request $request)
    {
        $input = Input::all();

        $validator = Validator::make($input, RoadObjectTypeModel::$rules);

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
                $this->roadObjectType->create($input);
                
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
        $roadObjectType = $this->roadObjectType->find($id);

        $data['roadObjectType'] = $roadObjectType;
        
        return View::make($this->view_path.'.edit', $data);
    }
    
    public function update(Request $request, $id)
    {
        $input = Input::all();

        $validator = Validator::make($input, RoadObjectTypeModel::$rulesUpdate);
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
                $this->roadObjectType->update($id, $input);
                
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
            $this->roadObjectType->delete($id);

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

    public function getDatatableList(Request $request)
    {
        return $this->roadObjectType->getDatatableList(@$request);
    }
}
