<?php namespace reference;

// Repositories
use reference\ContactTypeRepository as ContactType;

// Models
use reference\ContactType as ContactTypeModel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use Session;
use Illuminate\Http\FileHelper;
use ImageHelper;
use Image;

class ContactTypeController extends Controller
{
    public function __construct(ContactType $contactType) {

        $this->view_path = "listing.reference.contactType";
        $this->contactType = $contactType;
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

        $validator = Validator::make($input, ContactTypeModel::rules(0));

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
                $contactType = $this->contactType->create($input);
                
                $image = Input::file('image');
                
				if(isset($image))
				{
                    $img = Image::make(file_get_contents($image))->fit(98)->encode('data-url');

                    $contactType->image64 = $img;
                    
					$contactType->save();
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
        $cType = $this->contactType->find($id);
        
        $data['contactType'] = $cType;
        
        return View::make($this->view_path.'.edit', $data);
    }
    
    public function update(Request $request, $id)
    {
        $input = Input::all();

        $validator = Validator::make($input, ContactTypeModel::rules($id));
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
                $contactType = $this->contactType->update($id, $input);
                $image = Input::file('image');
                
				if(isset($image))
				{
                    $img = Image::make(file_get_contents($image))->fit(98)->encode('data-url');

                    $contactType->image64 = $img;
                    
					$contactType->save();
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
            $this->contactType->delete($id);

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
        return $this->contactType->getDatatableList(@$request);
    }

    public function showIcon($id)
    {
        $cType = $this->contactType->find(@$id);

        $data['contactType'] = $cType->image64;

        return View::make($this->view_path.'.show_image', $data);
    }
}
