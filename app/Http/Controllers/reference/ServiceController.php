<?php namespace reference;

// Repositories
use reference\ServiceRepository as Service;

// Models
use reference\Service as ServiceModel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use Session;
use Illuminate\Http\FileHelper;

class ServiceController extends Controller
{
    public function __construct(Service $service) {

        $this->view_path = "listing.reference.service";
        $this->service = $service;
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

        $validator = Validator::make($input, ServiceModel::rules(0));

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
                $this->service->create($input);

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
        $service = $this->service->find($id);
        $services = ServiceModel::whereNull('parent_id')->get();
        
        $data['service'] = $service;
        $data['services'] = $services;
        
        return View::make($this->view_path.'.edit', $data);
    }
    
    public function update($id)
    {
        $input = Input::all();
        $validator = Validator::make($input, ServiceModel::rules($id));
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
                $this->service->update($id, $input);
                
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
            $this->service->delete($id);

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
        return $this->service->getDatatableList(@$request);
    }

    public function getByTree()
    {
        $input = Input::all();
        $services = $this->service->getByTree(@$input['q']);

        return json_encode($services);
    }

    public function showTree()
    {
        return View::make($this->view_path.'.show');
    }

    public function getByChildren()
    {
        $input = Input::all();

        $services = $this->service->getByChildren(@$input['node_id']);

        return json_encode($services);
    }
}
