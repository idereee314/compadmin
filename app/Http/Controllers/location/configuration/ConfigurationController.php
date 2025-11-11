<?php

namespace location\configuration;

// Repositories
use location\configuration\ConfigurationRepository as Configuration;
use location\reference\RoadObjectTypeRepository as RoadObjectType;

// Models
use location\configuration\Configuration as ConfigurationModel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use Session;
use Illuminate\Http\FileHelper;

class ConfigurationController extends Controller
{
    public function __construct(Configuration $configuration, RoadObjectType $roadObjectType) {

        $this->view_path = "location.configuration";
        $this->configuration = $configuration;
        $this->roadObjectType = $roadObjectType;
    }
    
    public function index()
    {
        $data['view_path'] = $this->view_path;

        return View::make($this->view_path.'.index', $data);
    }
    
    public function create()
    {
        $roadTypes = $this->roadObjectType->all();

        $data['road_types'] = $roadTypes;

        return View::make($this->view_path.'.add', $data);
    }
    
    public function store(Request $request)
    {
        $input = Input::all();

        $validator = Validator::make($input, ConfigurationModel::$rules);

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
                $this->configuration->create($input);
                
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
    }
    
    public function edit($id)
    {
        $configuration = $this->configuration->find($id);
        $roadTypes = $this->roadObjectType->all();

        $data['road_types'] = $roadTypes;
        $data['configuration'] = $configuration;
        
        return View::make($this->view_path.'.edit', $data);
    }
    
    public function update(Request $request, $id)
    {
        $input = Input::all();

        $validator = Validator::make($input, ConfigurationModel::$rulesUpdate);
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
                $this->configuration->update($id, $input);
                
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

        // dd($response['errors']);

        $data['response'] = $response;
        return View::make('core.alert.messages', $data);
    }
    
    public function destroy($id)
    {
        try {
            $this->configuration->delete($id);

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
        return $this->configuration->getDatatableList(@$request);
    }
}
