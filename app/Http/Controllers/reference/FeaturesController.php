<?php namespace reference;

// Repositories
use reference\FeaturesRepository as Features;

// Models
use reference\Features as FeaturesModel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use Session;
use Illuminate\Http\FileHelper;
use Image;

class FeaturesController extends Controller
{
    public function __construct(Features $features) {

        $this->view_path = "listing.reference.features";
        $this->features = $features;
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

        $validator = Validator::make($input, FeaturesModel::$rules);

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
                $features = $this->features->create($input);
                
                $image = Input::file('image');
	
				if(isset($image))
				{
                    $img = Image::make(file_get_contents($image))->fit(98)->encode('data-url');

                    $features->image64 = $img;
                    
					$features->save();
				}

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
        $features = $this->features->find($id);
        
        $data['feature'] = $features;
        
        return View::make($this->view_path.'.edit', $data);
    }
    
    public function update($id)
    {
        $input = Input::all();

        $validator = Validator::make($input, FeaturesModel::$updateRules);
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
                $features = $this->features->update($id, $input);
                $image = Input::file('image');

				if(isset($image))
				{
                    $img = Image::make(file_get_contents($image))->fit(98)->encode('data-url');

                    $features->image64 = $img;
                    
					$features->save();
				}
                
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
            $this->features->delete($id);

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
        return $this->features->getDatatableList(@$request);
    }

    public function showIcon($id)
    {
        $feature = $this->features->find(@$id);

        $data['feature'] = $feature->image64;

        return View::make($this->view_path.'.show_image', $data);
    }
}
