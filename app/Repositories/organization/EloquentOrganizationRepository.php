<?php namespace organization;

use organization\Organization;
use core\sessions\Sessions;

use Hash;
use Log;
use ConfigHelper;
use DateHelper;
use DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Builder;

use SecurityHelper;
use Carbon;
use Session;
use Config;

class EloquentOrganizationRepository implements OrganizationRepository {

	public function all()
	{
		return Organization::all();
	}

	public function allPaginate()
	{
		return Organization::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}
 
	public function find($id)
	{
		return Organization::find($id);
	}

	    public function create($input)
    {
        $organization = new Organization;

        $organization->name = @$input['name'];
        $organization->name_en = @$input['name_en'];
        $organization->description = @$input['description'];
        $organization->parent_id = @$input['parent_id'];
        $organization->is_fulltime = @$input['is_fulltime'] ? $input['is_fulltime'] : false;
        $organization->keywords = '{'.$input['keywords'].'}';
        $organization->type_id = @$input['organization_type'];

        $organization->save();

        if ($organization->is_fulltime) 
        {
            for ($i=1; $i <= 7; $i++) { 
                $organizationWorktime = new OrganizationWorktime;

                $organizationWorktime->work_day = $i;
                $organizationWorktime->start_time = Config::get('smart.full_time')[1];
                $organizationWorktime->end_time = Config::get('smart.full_time')[2];
                $organizationWorktime->organization_id = @$organization->id;
    
                $organizationWorktime->save();
            }
        }

        $organization->categories()->attach(@$input['category']);
        $organization->features()->attach(@$input['features']);
    }

    public function delete($id)
    {
        $organization = Organization::find($id);
        $organization->delete();
    }

    public function update($id, $data)
    {
        $organization = Organization::find($id);

        $organization->name = @$data['name'];
        $organization->name_en = @$data['name_en'];
        $organization->description = @$data['description'];
        $organization->parent_id = @$data['parent_id'];
        $organization->is_active = @$data['is_active'] ? $data['is_active'] : false;
        $organization->is_fulltime = @$data['is_fulltime'] ? $data['is_fulltime'] : false;
        $organization->keywords = '{'.$data['keywords'].'}';
        $organization->type_id = @$data['organization_type'];

        $organization->save();
        
        if ($organization->is_fulltime) 
        {
            $organization->worktimes()->delete();

            for ($i=1; $i <= 7; $i++) { 
                $organizationWorktime = new OrganizationWorktime;

                $organizationWorktime->work_day = $i;
                $organizationWorktime->start_time = Config::get('smart.full_time')[1];
                $organizationWorktime->end_time = Config::get('smart.full_time')[2];
                $organizationWorktime->organization_id = @$organization->id;
    
                $organizationWorktime->save();
            }
        }

        $organization->categories()->sync(@$data['category']);
        $organization->features()->sync(@$data['features']);
    }

    public function getDatatableList($searchData)
    {
        $organization = Organization::select('rti_organization.*');
        
        $data = Datatables::of($organization)
        ->filter(function ($organization) use ($searchData) {
            if($searchData->has('name') && $searchData->get('name') !== null)
            {
                $organization->whereRaw('LOWER(name) like ?', array('%'.mb_strtolower($searchData->get('name')).'%'));
            }

            if($searchData->has('description') && $searchData->get('description') !== null)
            {
                $organization->whereRaw('LOWER(description) like ?', array('%'.mb_strtolower($searchData->get('description')).'%'));
            }
            
            if($searchData->has('is_active') && $searchData->get('is_active') !== null)
            {
                $organization->where('is_active', $searchData->get('is_active'));
            }
            
            if($searchData->has('categories') && $searchData->get('categories') !== null)
            {
                $parents = Category::whereIn('id', $searchData->get('categories'))->get();
                $categories = $parents->map(function ($item, $key) {
                    return collect($item->id)->merge(@$item->children->pluck('id'));
                });

                $categoryIds = $categories->toArray()[0];

                $organization->whereHas('categories', function($q) use ($categoryIds){
                    $q->whereIn('id', $categoryIds);
                });
            }
            
            if($searchData->has('org_status') && $searchData->get('org_status') !== null)
            {
                $organization->where('status_id', $searchData->get('org_status'));
            }
            
            if($searchData->has('org_filter') && $searchData->get('org_filter') !== null)
            {                    
                if (in_array('no_address', $searchData->get('org_filter'))) {
                    $organization->whereDoesntHave('address');
                }
                if (in_array('no_location', $searchData->get('org_filter'))) {
                    $organization->whereHas('address', function($q){
                        $q->whereNull('object_location_id');
                    });
                }
                if (in_array('no_time', $searchData->get('org_filter'))) {
                    $organization->has('worktimes', '<', '5');
                }
                if (in_array('no_image', $searchData->get('org_filter'))) {
                    $organization->has('picture', '<', '3');
                }
                if (in_array('no_contact', $searchData->get('org_filter'))) {
                    $organization->whereDoesntHave('contact');
                }
                if (in_array('no_service', $searchData->get('org_filter'))) {
                    $organization->whereDoesntHave('services');
                }
            }
        })
        ->editColumn('status_id', function ($organization) {
            return @$organization->organizationStatusLast->name;
        })
        ->editColumn('parent_id', function ($organization) {
            return @$organization->parent->name;
        })
        ->editColumn('categories', function ($organization) {
            return $categoryNames = $organization->categories->implode('name', ', ');
        })
        ->editColumn('description', function ($organization) {
            return Str::words(@$organization->description, $limit = 10, $end = '...');
        })
        ->editColumn('is_active', function ($organization) {
            return Config::get('smart.is_active')[$organization->is_active];
        })
        ->editColumn('is_fulltime', function ($organization) {
            return Config::get('smart.is_fulltime')[$organization->is_fulltime];
        })
        ->addColumn('action', function ($org) {
            $actionHtml = "";
            $actionHtml = '<div class="btn-group dropup">';
            $actionHtml .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">';
            $actionHtml .= '<i class="fa fa-server"></i>';
            $actionHtml .= '<span class="sr-only">Toggle Dropdown</span>';
            $actionHtml .= '</button>';
            $actionHtml .= '<ul class="dropdown-menu pull-right">';
            
            $actionHtml .= '<li><a href="'.route('organization.edit', $org->id).'">'.trans('display.general_edit').'</a></li>';
            $actionHtml .= '<li><a href="#" onclick="copyOrganization('.$org->id.')">'.trans('display.general_copy').'</a></li>';

            $actionHtml .= '<li class="divider"></li>';
            
            $actionHtml .= '<li><a href="#" name="source-data" onclick="organizationDelete('.$org->id.')" class="organization-delete" data-organizationid="'.$org->id.'">'.trans('display.general_delete').'</a>';
                           
            $actionHtml .= '</ul>';
            $actionHtml .= '</div>';

            return $actionHtml;
        })->make(true);

        return $data;
    }

    public function attachEvent($input)
    {
        foreach ($input['event'] as $key => $value) {
            $organization = $this->find($input['organization_id']);
            $organization->event()->attach($value);
        }
    }

    public function detachEvent($id, $input)
    {
        $organization = $this->find($input['orgId']);
        $organization->event()->detach($id);
    }

	public function byParent($orgName)
    {
        //DB::enableQueryLog();
        $parent = "";
        $qry = Organization::select('*')->whereNull('parent_id');

        if(!empty(@$orgName))
		{
			$qry->whereRaw("LOWER(name) like ?", array('%'.mb_strtolower(@$orgName).'%'));
        }
        $parent = $qry->orderBy('name', 'asc')->get();
        /*
        $queries = DB::getQueryLog();
        dd($queries);
        */
		return $parent;
    }

	public function findOrganizationByName($orgName)
    {
        //DB::enableQueryLog();
        $organization = "";

        if(!empty(@$orgName))
		{
			$qry = Organization::select('*');
			$qry->whereRaw("LOWER(name) like ?", array('%'.mb_strtolower(@$orgName).'%'));
			$organization = $qry->orderBy('name', 'asc')->get();
        }
        /*
        $queries = DB::getQueryLog();
        dd($queries);
        */
		return $organization;
    }

}
