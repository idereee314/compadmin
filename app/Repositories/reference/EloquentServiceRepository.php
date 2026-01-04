<?php 

namespace reference;

use reference\Service;

use Datatables;
use Session;
use Config;
use \DB;

class EloquentServiceRepository implements ServiceRepository
{
    public function find($id)
    {
        return Service::find($id);
    }

    public function all()
    {
        return Service::all();
    }

    public function create($input)
    {
        $service = new Service;
        $service->name = @$input['name'];
        $service->description = @$input['description'];
        $service->parent_id = @$input['parent_id'];

        $service->save();
    }

    public function delete($id)
    {
        $service = Service::find($id);
        $service->delete();
    }

    public function update($id, $data)
    {
        $service = Service::find($id);

        $service->name = @$data['name'];
        $service->description = @$data['description'];
        $service->parent_id = @$data['parent_id'];
        $service->is_active = @$data['is_active'] ? $data['is_active'] : false;

        $service->save();
    }

    public function getDatatableList($searchData)
    {
        $service = Service::select('*');
        
        $data = Datatables::of($service)
        ->filter(function ($service) use ($searchData) {
            if($searchData->has('name') && $searchData->get('name') !== null)
            {
                $service->whereRaw('LOWER(name) like ?', array('%'.mb_strtolower($searchData->get('name')).'%'));
            }
        })
        ->editColumn('is_active', function ($service) {
            return Config::get('smart.is_active')[$service->is_active];
        })
        ->addColumn('parent', function($service){
            $parent = "";
            if(count($service->parents) > 0)
            {
                $parent = $service->parents->reverse()->pluck("name")->implode(' -> ');
            }
            return $parent;
        })
        ->addColumn('action', function ($ser) {
            $actionHtml = "";
            $actionHtml = '<div class="btn-group dropup">';
            $actionHtml .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">';
            $actionHtml .= '<i class="fa fa-server"></i>';
            $actionHtml .= '<span class="sr-only">Toggle Dropdown</span>';
            $actionHtml .= '</button>';
            $actionHtml .= '<ul class="dropdown-menu pull-right">';
            
            $actionHtml .= '<li><a href="javascript:;" class="edit-service" data-serviceid="'.$ser->id.'">'.trans('display.general_edit').'</a></li>';            
            $actionHtml .= '<li><a href="javascript:;" class="delete-service" data-serviceid="'.$ser->id.'">'.trans('display.general_delete').'</a>';
                           
            $actionHtml .= '</ul>';
            $actionHtml .= '</div>';

            return $actionHtml;
        })
        ->make(true);

        return $data;
    }

    public function getOnlyParents()
    {
        return Service::whereNull('parent_id')->get();
    }

    public function getByTree($q = null, $node = null)
    {
        //DB::enableQueryLog();
        $services = "";
        $qry = Service::select('*');
        
        if(!empty(@$q))
		{
			$qry->whereRaw("LOWER(name) like ?", array('%'.mb_strtolower(@$q).'%'));
        }
        else
        {
            $qry->whereNull('parent_id');
        }
        $qry->with(['children' => function($qr) use($q)
        {
            $qr->orWhereRaw("LOWER(name) like ?", array('%'.mb_strtolower(@$q).'%'));
        }])->withCount(['child']);
        $services = $qry->orderBy('name', 'asc')->get();
        /*
        $queries = DB::getQueryLog();
        dd($queries);
        */
		return $services;
    }

    public function getByChildren($node = null)
    {
        //DB::enableQueryLog();
        $services = "";
        $qry = Service::select('*');

        if(!empty(@$node))
        {
            $qry->where('parent_id', @$node);
        }
        else 
        {
            $qry->whereNull('parent_id');
        }
        $qry->withCount(['child']);
        $services = $qry->orderBy('name', 'asc')->get();
        /*
        $queries = DB::getQueryLog();
        dd($queries);
        */
		return $services;
    }

    public function getServicesByCategoryId($categoiryId)
    {
        $services = "";
        if(!empty(@$categoiryId))
        {
            $qry = Service::whereHas('categories', function($q) use($categoiryId){
                $q->where('id', $categoiryId);
            });
            $services = $qry->orderBy('name', 'asc')->get();
        }   

        return $services;
    }
}