<?php namespace reference;

// Repositories
use reference\CategoryRepository as Category;
use reference\ServiceRepository as Service;

// Models
use reference\Category as CategoryModel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use File;
use Session;
use ImageHelper;
use Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\FileHelper;
use DB;

class CategoryController extends Controller
{
    public function __construct(Category $category, Service $service) {

        $this->view_path = "listing.reference.category";
        $this->category = $category;
        $this->service = $service;
    }

    public function index()
    {
        $data['view_path'] = $this->view_path;

        return View::make($this->view_path.'.index', $data);
    }

    public function create()
    {
        $category = $this->category->getOnlyParents();
        $lastShowOrder = $this->category->getLastShowOrder();

        $data['categories'] = $category;
        $data['bg_colors'] = @Config::get('enums.bg_classes');
        $data['show_order'] = $lastShowOrder;
        $data['categoryTypes'] = @Config::get('enums.category_type');

        return View::make($this->view_path.'.add', $data);
    }

    public function store(Request $request)
    {
        $input = Input::all();

        $validator = Validator::make($input, CategoryModel::rules(0));

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
                $category = $this->category->create($input);
                
                $image = Input::file('image');
                $coverImage = Input::file('cover_image');
                
				if(isset($image))
				{
                    $img = Image::make(file_get_contents($image))->fit(98)->encode('data-url');

                    $category->image64 = $img;
                    
					$category->save();
				}
                
				if(isset($coverImage))
				{
                    $img = Image::make(file_get_contents($coverImage))->fit(320, 210)->encode('data-url');

                    $imageUrl = ImageHelper::uploadImage64ToDirectory($img, 'routyweb', 'upload_category_cover', 'category');

					$category->cover_url = $imageUrl;
					$category->save();
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
		$data['tabs'] = Config::get('listing.category_tabs');
		$data['cat_id'] = $id;
        $data['tab_id'] = 'tab2-1';
        $data['view_path'] = $this->view_path;
        
        return View::make($this->view_path.'.edit', $data);
    }

    public function update($id)
    {
        $input = Input::all();

        $validator = Validator::make($input, CategoryModel::rules($id));

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
                $category = $this->category->update($id, $input);
                
                $image = Input::file('image');
                $coverImage = Input::file('cover_image');
                
				if(isset($image))
				{
                    $img = Image::make(file_get_contents($image))->fit(98)->encode('data-url');

                    $category->image64 = $img;
                    
					$category->save();
				}
                
				if(isset($coverImage))
				{
                    if($category->cover_url)
                    {
                        if(file_exists(Config::get('smart.upload_image_dir')['routyweb'].$category->cover_url))
                        {
                            unlink(Config::get('smart.upload_image_dir')['routyweb'].$category->cover_url);
                        }
                    }

                    $img = Image::make($coverImage)->fit(320, 210)->encode('data-url');

                    $imageUrl = ImageHelper::uploadImage64ToDirectory($img, 'routyweb', 'upload_category_cover', 'category');

					$category->cover_url = $imageUrl;
					$category->save();
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
            $this->category->delete($id);

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
        return $this->category->getDatatableList(@$request);
    }

    public function showIcon($id)
    {
        $category = $this->category->find(@$id);
        $data['category'] = $category;
    
        return View::make($this->view_path.'.show_image', $data);
    }

    public function removeImage()
    {
        $files = glob(Config::get('smart.upload_image_dir')['routyadmin'].'/*'); // get all file names
        foreach($files as $file){ // iterate files
            if(is_file($file)) {
                unlink($file); // delete file
            }
        }
    }

    public function getLastShowOrderByParent()
    {
        $input = Input::all();

        return $this->category->getLastShowOrderByParent($input['categoryId']);
    }

    public function searchServices()
    {
        $input = Input::all();

        $services = $this->service->byParent($input['q']);
        return json_encode($services);
    }

	public function includeTab()
    {
		$input = Input::all();

        $category = $this->category->find($input['id']);
        
        if($input['code'] == 'general')
        {
            $categories = CategoryModel::whereNull('parent_id')->get();
    
            $selectedServices = $category->services->pluck('id')->toArray();
    
            if (@$category->cover_url) {
                if(file_exists(Config::get('smart.upload_image_dir')['routyweb'].$category->cover_url))
                {
                    $imageName = ImageHelper::after('category/', @$category->cover_url);
        
                    $imageFile = base64_encode(file_get_contents(Config::get('smart.upload_image_dir')['routyweb'].$category->cover_url));
        
                    $savePath = Config::get('smart.upload_image_dir')['routyadmin'].'/'.$imageName;
        
                    Image::make(base64_decode($imageFile))->save($savePath);
        
                    $data['coverImage'] = $imageName;
                }
            }
    
            $data['bg_colors'] = @Config::get('enums.bg_classes');
            $data['coverImage'] = false;
            $data['selectedServices'] = $selectedServices;
            $data['categories'] = $categories;
            $data['categoryTypes'] = @Config::get('enums.category_type');
        }
        else
        {
            
		}

        $data['category'] = $category;
        $data['tab_id'] = $input['tab_id'];
        $data['view_path'] = $this->view_path;

        return View::make($this->view_path.'.'.$input['name'], $data);
    }

    public function addService()
    {
        $input = Input::all();
        $category = $this->category->find($input['categoryId']);

        $data['category'] = @$category;

        return View::make($this->view_path.'.service_add', $data);
    }

    public function attachService($id)
    {
        $input = Input::all();
        $category = $this->category->find($id);

        try {
            $services = explode(",", @$input['service_id']);

            $category->services()->sync($services, false);
            
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
            
        $data['response'] = $response;
        return View::make('core.alert.messages', $data);
    }

    public function detachService($id)
    {
        $input = Input::all();
        $category = $this->category->find($id);
        
        try {
            $category->services()->detach($input['service_id']);
            
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

    public function getByChildren()
    {
        $input = Input::all();
        $categories = $this->category->getByChildren(@$input['node_id']);

        return json_encode($categories);
    }
}
